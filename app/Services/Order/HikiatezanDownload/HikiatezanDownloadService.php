<?php

namespace App\Services\Order\HikiatezanDownload;

// モデル
use App\Models\OrderItem;
// その他
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
// 列挙
use App\Enums\SystemEnum;

class HikiatezanDownloadService
{
    // ダウンロード対象を取得
    public function getDownloadTarget($chk)
    {
        // 引当残を商品コード毎で集計して、1以上の結果を取得
        return OrderItem::select(
                    'order_items.order_item_code',
                    'items.item_jan_code',
                    'items.item_name',
                    'items.manufacturer',
                    'items.supplier',
                    DB::raw('SUM(order_items.unallocated_quantity) as total_unallocated_quantity')
                )
                ->join('items', 'items.item_code', '=', 'order_items.order_item_code')
                ->whereIn('order_items.order_control_id', $chk)
                ->groupBy('order_items.order_item_code', 'items.item_jan_code', 'items.item_name', 'items.manufacturer', 'items.supplier')
                ->having('total_unallocated_quantity', '>=', 1)
                ->orderBy('order_items.order_item_code');
    }

    // ダウンロードするデータを取得
    public function getDownloadData($hikiatezan)
    {
        // チャンクサイズを指定
        $chunk_size = 1000;
        $response = new StreamedResponse(function () use ($hikiatezan, $chunk_size){
            // ハンドルを取得
            $handle = fopen('php://output', 'wb');
            // BOMを書き込む
            fwrite($handle, "\xEF\xBB\xBF");
            // ヘッダーを書き込む
            $header = ['商品コード', '商品JANコード', '商品名', 'メーカー', '仕入先', '引当残'];
            fputcsv($handle, $header);
            // レコードをチャンクごとに書き込む
            $hikiatezan->chunk($chunk_size, function ($hikiatezan) use ($handle){
                // 引当残の分だけループ処理
                foreach($hikiatezan as $item){
                    // 変数に情報を格納
                    $row = [
                        $item->order_item_code,
                        $item->item_jan_code,
                        $item->item_name,
                        $item->manufacturer,
                        $item->supplier,
                        $item->total_unallocated_quantity,
                    ];
                    // 書き込む
                    fputcsv($handle, $row);
                };
            });
            // ファイルを閉じる
            fclose($handle);
        });
        return $response;
    }
}