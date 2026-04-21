<?php

namespace App\Services\Shipping\ShippingHistory;

// モデル
use App\Models\Order;
// 列挙
use App\Enums\SystemEnum;
use App\Enums\MallEnum;
use App\Enums\OrderCategoryEnum;
use App\Enums\ShippingActualEnum;
// その他
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Carbon\CarbonImmutable;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\LazyCollection;

class ShippingActualDownloadService
{
    // ファイルを出力するディレクトリを作成
    public function makeDirectory($nowDate)
    {
        // 保存先のディレクトリ名を決める
        $directory_name = "【" . SystemEnum::CUSTOMER_NAME_JP . "様】出荷実績データ_" . $nowDate->format('Y年m月d日H時i分s秒');
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
    public function createFile($nowDate, $orders, $directory_path)
    {
        // ダウンロード対象の受注管理IDを取得
        $order_control_ids = $orders->pluck('order_control_id');
        // 受注区分×荷送人の組み合わせを取得
        $create_groups = Order::join('order_categories', 'order_categories.order_category_id', 'orders.order_category_id')
                            ->join('malls', 'malls.mall_id', 'order_categories.mall_id')
                            ->whereIn('order_control_id', $order_control_ids)
                            ->select('orders.order_category_id', 'order_category_name', 'malls.mall_id', 'malls.mall_name')
                            ->distinct()
                            ->get();
        // 受注区分の分だけループ処理
        foreach($create_groups as $create_group){
            // 受注区分の条件に一致する受注を取得
            $create_orders = Order::whereIn('order_control_id', $order_control_ids)
                                ->where('order_category_id', $create_group->order_category_id);
            // モールによって出力内容が変わるので、処理を分岐
            // QOO10の場合
            if($create_group->mall_id === MallEnum::QOO10_ID){
                // 出荷実績データを作成
                $this->createShippingActualFileAtQoo10($nowDate, $create_group, $create_orders, $directory_path);
            }
            // shopifyの場合
            if($create_group->mall_id === MallEnum::SHOPIFY_ID){
                // 出荷実績データを作成
                $this->createShippingActualFileAtShopify($nowDate, $create_group, $create_orders, $directory_path);
            }
        }
    }

    // 出荷実績データを作成
    public function createShippingActualFileAtQoo10($nowDate, $create_group, $create_orders, $directory_path)
    {
        // ファイル名を取得
        $file_name = $nowDate->format('Ymd') . "_出荷実績データ【" . $create_group->order_category_name . "】【" . $create_group->mall_name . "】【" . SystemEnum::CUSTOMER_NAME_JP . "】.xlsx";
        // ファイルパスを取得
        $file_path = $directory_path . '/' . $file_name;
        // 一時ファイルパスを生成
        $temp_file_path = tempnam(sys_get_temp_dir(), 'xlsx_');
        // Spreadsheetを作成
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // フォントを設定
        $spreadsheet->getDefaultStyle()->getFont()
            ->setName('Arial')
            ->setSize(11);
        // ヘッダー行を書き込む
        $headers = ShippingActualEnum::QOO10_HEADER;
        $sheet->fromArray($headers, null, 'A1');
        // データ行を書き込む
        $row_index = 2;
        $create_orders->cursor()->each(function ($order) use ($sheet, &$row_index) {
            $sheet->fromArray([
                '配送要請',
                '',
                $order->order_no,
                ShippingActualEnum::qoo10_shipping_method_get($order->shipping_method_id),
                $order->tracking_no,
                CarbonImmutable::parse($order->shipping_date)->format('Ymd'),
                '', '', '', '', '', '', '', '', '',
                '', '', '', '', '', '', '', '', '', '',
                '', 'JP', '', '', '', '', '', '', '', '',
                '', '', '', '', '', '', '', '', '', '',
                '', '', '', '',
            ], null, 'A' . $row_index);
            $row_index++;
        });
        // xlsxとして保存
        $writer = new Xlsx($spreadsheet);
        $writer->save($temp_file_path);
        // Storageに保存して一時ファイルを削除
        Storage::disk('public')->put($file_path, file_get_contents($temp_file_path));
        unlink($temp_file_path);
    }

    // 出荷実績データを作成
    public function createShippingActualFileAtShopify($nowDate, $create_group, $create_orders, $directory_path)
    {
        // ファイル名を取得
        $file_name = $nowDate->format('Ymd') . "_出荷実績データ【" . $create_group->order_category_name . "】【" . $create_group->mall_name . "】【" . SystemEnum::CUSTOMER_NAME_JP . "】.xlsx";
        // ファイルパスを取得
        $file_path = $directory_path . '/' . $file_name;
        // 一時ファイルパスを生成
        $temp_file_path = tempnam(sys_get_temp_dir(), 'xlsx_');
        // Spreadsheetを作成
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // フォントを設定
        $spreadsheet->getDefaultStyle()->getFont()
            ->setName('Arial')
            ->setSize(11);
        // ヘッダー行を書き込む
        $headers = ShippingActualEnum::SHOPIFY_HEADER;
        $sheet->fromArray($headers, null, 'A1');
        // データ行を書き込む
        $row_index = 2;
        $create_orders->cursor()->each(function ($order) use ($sheet, &$row_index) {
            $sheet->fromArray([
                $order->order_no,
                'UPDATE',
                'Fulfillment Line',
                'success',
                ShippingActualEnum::shopify_shipping_method_get($order->shipping_method_id),
                str_replace(',', ';', $order->tracking_no),
                'yes',
            ], null, 'A' . $row_index);
            $row_index++;
        });
        // xlsxとして保存
        $writer = new Xlsx($spreadsheet);
        $writer->save($temp_file_path);
        // Storageに保存して一時ファイルを削除
        Storage::disk('public')->put($file_path, file_get_contents($temp_file_path));
        unlink($temp_file_path);
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