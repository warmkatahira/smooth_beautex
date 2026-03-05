<?php

namespace App\Services\Stock\StockHistory;

// モデル
use App\Models\StockHistory;
// その他
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class StockHistoryDownloadService
{
    // ダウンロードするデータを取得
    public function getDownloadData($stock_histories)
    {
        //dd($stock_histories->first());
        // チャンクサイズを指定
        $chunk_size = 1000;
        $response = new StreamedResponse(function () use ($stock_histories, $chunk_size){
            // ハンドルを取得
            $handle = fopen('php://output', 'wb');
            // BOMを書き込む
            fwrite($handle, "\xEF\xBB\xBF");
            // システムに定義してあるヘッダーを取得し、書き込む
            $header = StockHistory::downloadHeader();
            fputcsv($handle, $header);
            // レコードをチャンクごとに書き込む
            $stock_histories->chunk($chunk_size, function ($stock_histories) use ($handle){
                // 在庫履歴の分だけループ
                foreach($stock_histories as $stock_history){
                    $row = [
                        CarbonImmutable::parse($stock_history->updated_at)->isoFormat('Y年MM月DD日'),
                        CarbonImmutable::parse($stock_history->updated_at)->isoFormat('HH:mm:ss'),
                        $stock_history->stock_history_category_name,
                        $stock_history->user?->full_name,
                        $stock_history->base_name,
                        $stock_history->item_code,
                        $stock_history->item_jan_code,
                        $stock_history->item_name,
                        $stock_history->item_category,
                        $stock_history->quantity,
                        $stock_history->comment,
                    ];
                    fputcsv($handle, $row);
                };
            });
            // ファイルを閉じる
            fclose($handle);
        });
        return $response;
    }
}