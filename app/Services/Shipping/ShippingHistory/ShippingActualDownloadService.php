<?php

namespace App\Services\Shipping\ShippingHistory;

// モデル
use App\Models\Order;
// その他
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class ShippingActualDownloadService
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
            $header = Order::downloadHeaderAtShippingActual();
            fputcsv($handle, $header);
            // レコードをチャンクごとに書き込む
            $orders->chunk($chunk_size, function ($orders) use ($handle){
                // 受注の分だけループ処理
                foreach($orders as $order){
                    // 変数に情報を格納
                    $row = [
                        $order->order_no,
                        $order->delivery_company_and_shipping_method,
                        $order->tracking_no,
                        CarbonImmutable::parse($order->shipping_date)->format('Ymd'),
                        'JP',
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