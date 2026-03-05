<?php

namespace App\Services\Stock\Stock;

// モデル
use App\Models\Stock;
// その他
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;
use App\Enums\RouteNameEnum;

class StockDownloadService
{
    // ダウンロードするデータを取得
    public function getDownloadData($stocks, $bases, $route_name)
    {
        // システムに定義してあるヘッダーを取得し、書き込む
        // チャンクサイズを指定
        $chunk_size = 1000;
        $response = new StreamedResponse(function () use ($stocks, $bases, $chunk_size, $route_name){
            // ハンドルを取得
            $handle = fopen('php://output', 'wb');
            // BOMを書き込む
            fwrite($handle, "\xEF\xBB\xBF");
            // システムに定義してあるヘッダーを取得し、書き込む
            $header = Stock::downloadHeader($route_name, $bases);
            fputcsv($handle, $header);
            // レコードをチャンクごとに書き込む
            $stocks->chunk($chunk_size, function ($stocks) use ($handle, $bases, $route_name){
                // 在庫の分だけループ
                foreach($stocks as $stock){
                    // 商品単位表示の場合
                    if($route_name === RouteNameEnum::STOCK_BY_ITEM){
                        $row = [
                            $stock->item_code,
                            $stock->item_jan_code,
                            $stock->item_name,
                            $stock->item_category,
                            $stock->is_stock_managed_text,
                        ];
                        // 倉庫の分だけループ処理
                        foreach($bases as $base){
                            // 各在庫数をセット
                            $row[] = $stock->{'total_stock_'.$base->base_id} ?? 0;
                            $row[] = $stock->{'total_order_quantity_'.$base->base_id} ?? 0;
                            $row[] = $stock->{'available_stock_'.$base->base_id} ?? 0;
                        }
                    }
                    // 在庫単位表示の場合
                    if($route_name === RouteNameEnum::STOCK_BY_STOCK){
                        $row = [
                            $stock->base_name,
                            $stock->item_code,
                            $stock->item_jan_code,
                            $stock->item_name,
                            $stock->item_category,
                            $stock->item_location,
                            $stock->is_stock_managed_text,
                            $stock->total_stock,
                            $stock->total_order_quantity,
                            $stock->available_stock,
                        ];
                    }
                    fputcsv($handle, $row);
                };
            });
            // ファイルを閉じる
            fclose($handle);
        });
        return $response;
    }
}