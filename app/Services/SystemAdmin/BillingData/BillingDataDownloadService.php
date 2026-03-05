<?php

namespace App\Services\SystemAdmin\BillingData;

// モデル
use App\Models\Order;
use App\Models\StockHistory;
use App\Models\StockHistoryCategory;
// 列挙
use App\Enums\SystemEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\StockHistoryCategoryEnum;
// その他
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Storage;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class BillingDataDownloadService
{
    // ダウンロード内容の確認
    public function checkDownload($request)
    {
        // 注文ステータスが「出荷待ち」よりも大きい場合
        if(is_null($request->billing_date)){
            throw new \RuntimeException('請求年月が指定されていません。');
        }
    }

    // ファイルを出力するディレクトリを作成
    public function makeDirectory($nowDate)
    {
        // 保存先のディレクトリ名を決める
        $directory_name = "【" . SystemEnum::CUSTOMER_NAME . "】請求データ_" . $nowDate->format('Y年m月d日H時i分s秒');
        // ディレクトリのパスを取得
        $directory_path = 'export/' . $directory_name;
        // 既に存在しているディレクトリではない場合
        if(!Storage::disk('public')->exists($directory_path)){
            // 保存先のディレクトリを作成
            Storage::disk('public')->makeDirectory($directory_path);
        }
        return compact('directory_name', 'directory_path');
    }

    // ファイルを作成
    public function createFile($nowDate, $request, $directory_path)
    {
        // 請求年月をフォーマット化
        $billing_date = CarbonImmutable::parse($request->billing_date)->format('Y年m月');
        // 請求年月の月初と月末の日付を取得
        $billing_date_from = CarbonImmutable::parse($request->billing_date)->startOfMonth()->toDateString();
        $billing_date_to = CarbonImmutable::parse($request->billing_date)->endOfMonth()->toDateString();
        // 出荷明細の作成対象を取得
        $orders = $this->getShippingReportTarget($billing_date_from, $billing_date_to);
        // 出荷明細を作成
        $this->createShippingReportFile($nowDate, $billing_date, $directory_path, $orders);
        // 入荷明細の作成対象を取得
        $nyukas = $this->getReceivingReportTarget($billing_date_from, $billing_date_to);
        // 入荷明細を作成
        $this->createReceivingReportFile($nowDate, $billing_date, $directory_path, $nyukas);
    }

    public function getShippingReportTarget($billing_date_from, $billing_date_to)
    {
        // 指定した年月の出荷済みの受注を取得
        return Order::with('shipping_method.delivery_company')
                ->withSum('order_items as total_order_quantity', 'order_quantity')
                ->where('order_status_id', OrderStatusEnum::SHUKKA_ZUMI)
                ->whereDate('shipping_date', '>=', $billing_date_from)
                ->whereDate('shipping_date', '<=', $billing_date_to)
                ->orderBy('shipping_date')
                ->orderBy('order_control_id');
    }

    // 出荷明細を作成
    public function createShippingReportFile($nowDate, $billing_date, $directory_path, $orders)
    {
        // ファイル名を取得
        $file_name = "【" . SystemEnum::CUSTOMER_NAME . "様】出荷明細_" . $billing_date . ".csv";
        // ファイルパスを取得
        $file_path = $directory_path . '/' . $file_name;
        // 一時ファイルを生成（PHPファイルシステム上）
        $temp_file = tmpfile();
        $meta = stream_get_meta_data($temp_file);
        $temp_file_path = $meta['uri'];
        // ファイルハンドルで書き込み
        $handle = fopen($temp_file_path, 'w');
        // ヘッダー行の書き込み
        $header = Order::downloadHeaderAtBilling();
        fputcsv($handle, array_map(fn($v) => mb_convert_encoding($v, 'SJIS-win', 'UTF-8'), $header));
        // チャンクサイズを指定
        $chunk_size = 1000;
        // レコードをチャンクごとに書き込む
        $orders->chunk($chunk_size, function ($chunk) use ($handle){
            // 受注の分だけループ処理
            foreach($chunk as $order){
                // 変数に情報を格納
                $row = [
                    $order->shipping_date,
                    $order->ship_name,
                    $order->shipping_method->delivery_company->delivery_company,
                    $order->shipping_method->shipping_method,
                    $order->tracking_no,
                    1,
                    $order->total_order_quantity,
                    '',
                ];
                // SJIS変換
                $sjisRow = array_map(fn($v) => mb_convert_encoding($v, 'SJIS-win', 'UTF-8'), $row);
                // 書き込む
                fputcsv($handle, $sjisRow);
            };
        });
        // ファイルを閉じる
        fclose($handle);
        // ファイルを保存
        Storage::disk('public')->put($file_path, file_get_contents($temp_file_path));
        // 一時ファイル削除
        fclose($temp_file);
    }

    // 入荷明細の作成対象を取得
    public function getReceivingReportTarget($billing_date_from, $billing_date_to)
    {
        // 入庫の在庫履歴区分IDを取得
        $stock_history_category_id = StockHistoryCategory::where('stock_history_category_name', StockHistoryCategoryEnum::NYUKO)->value('stock_history_category_id');
        // 指定した年月の入荷情報を取得
        return StockHistory::join('stock_history_details', 'stock_history_details.stock_history_id', 'stock_histories.stock_history_id')
                ->where('stock_history_category_id', $stock_history_category_id)
                ->whereDate('stock_histories.created_at', '>=', $billing_date_from)
                ->whereDate('stock_histories.created_at', '<=', $billing_date_to)
                ->select(DB::raw('DATE(stock_histories.created_at) as nyuka_date'), DB::raw('SUM(stock_history_details.quantity) as total_nyuka_quantity'))
                ->groupBy('nyuka_date')
                ->orderBy('nyuka_date');
    }

    // 入荷明細を作成
    public function createReceivingReportFile($nowDate, $billing_date, $directory_path, $nyukas)
    {
        // ファイル名を取得
        $file_name = "【" . SystemEnum::CUSTOMER_NAME . "様】入荷明細_" . $billing_date . ".csv";
        // ファイルパスを取得
        $file_path = $directory_path . '/' . $file_name;
        // 一時ファイルを生成（PHPファイルシステム上）
        $temp_file = tmpfile();
        $meta = stream_get_meta_data($temp_file);
        $temp_file_path = $meta['uri'];
        // ファイルハンドルで書き込み
        $handle = fopen($temp_file_path, 'w');
        // ヘッダー行の書き込み
        $header = StockHistory::downloadHeaderAtBilling();
        fputcsv($handle, array_map(fn($v) => mb_convert_encoding($v, 'SJIS-win', 'UTF-8'), $header));
        // チャンクサイズを指定
        $chunk_size = 1000;
        // レコードをチャンクごとに書き込む
        $nyukas->chunk($chunk_size, function ($chunk) use ($handle){
            // 受注の分だけループ処理
            foreach($chunk as $nyuka){
                // 変数に情報を格納
                $row = [
                    $nyuka->nyuka_date,
                    $nyuka->total_nyuka_quantity,
                ];
                // SJIS変換
                $sjisRow = array_map(fn($v) => mb_convert_encoding($v, 'SJIS-win', 'UTF-8'), $row);
                // 書き込む
                fputcsv($handle, $sjisRow);
            };
        });
        // ファイルを閉じる
        fclose($handle);
        // ファイルを保存
        Storage::disk('public')->put($file_path, file_get_contents($temp_file_path));
        // 一時ファイル削除
        fclose($temp_file);
    }

    // Zipファイルを作成
    public function createZip($directory_name, $directory_path)
    {
        // ZIPファイルの名前
        $zip_file_name = $directory_name.'.zip';
        // ZipArchiveクラスのインスタンス作成
        $zip = new ZipArchive;
        // ZIPファイルの保存パス(storageディレクトリ内)
        $zip_file_path = storage_path('app/public/export/'.$zip_file_name);
        // ZIPファイルを作成
        if($zip->open($zip_file_path, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE){
            // 対象ディレクトリ内のファイルを取得
            $files = Storage::disk('public')->files('export/' . $directory_name);
            // 各ファイルをZIPに追加
            foreach($files as $file){
                // フルパスを取得
                $full_path = Storage::disk('public')->path($file);
                // 日本語ファイル名の場合、Shift-JISに変換してZIPに追加
                $zip->addFile($full_path, mb_convert_encoding(basename($file), 'SJIS-win', 'UTF-8'));
            }
            // ZIPファイルを閉じる
            $zip->close();
        }
        // 元のディレクトリを削除
        Storage::disk('public')->deleteDirectory($directory_path);
        return $zip_file_path;
    }
}