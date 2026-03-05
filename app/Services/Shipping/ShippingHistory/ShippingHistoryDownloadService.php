<?php

namespace App\Services\Shipping\ShippingHistory;

// モデル
use App\Models\Order;
// その他
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class ShippingHistoryDownloadService
{
    // ダウンロードするデータを取得
    public function getDownloadData($orders)
    {
        // チャンクサイズを指定
        $chunk_size = 1000;
        $response = new StreamedResponse(function () use ($orders, $chunk_size){
            // ハンドルを取得
            $handle = fopen('php://output', 'wb');
            // BOMを書き込む
            fwrite($handle, "\xEF\xBB\xBF");
            // システムに定義してあるヘッダーを取得し、書き込む
            $header = Order::downloadHeaderAtShippingHistory();
            fputcsv($handle, $header);
            // レコードをチャンクごとに書き込む
            $orders->chunk($chunk_size, function ($orders) use ($handle){
                // 受注の分だけループ処理
                foreach($orders as $order){
                    // 商品の分だけループ処理
                    foreach($order->order_items as $order_item){
                        // 変数に情報を格納
                        $row = [
                            $order->shipping_date,
                            $order->order_import_date,
                            $order->order_import_time,
                            $order->order_no,
                            $order->order_date,
                            $order->order_control_id,
                            $order->order_category->order_category_name,
                            $order->base->base_name,
                            $order->ship_name,
                            $order->shipping_method->delivery_company->delivery_company,
                            $order->shipping_method->shipping_method,
                            $order->desired_delivery_date,
                            $order->desired_delivery_time,
                            $order->tracking_no,
                            $order_item->order_item_code,
                            $order_item->item->item_jan_code,
                            $order_item->item->item_name,
                            $order_item->order_quantity,
                        ];
                        // 書き込む
                        fputcsv($handle, $row);
                    }
                };
            });
            // ファイルを閉じる
            fclose($handle);
        });
        return $response;
    }
}