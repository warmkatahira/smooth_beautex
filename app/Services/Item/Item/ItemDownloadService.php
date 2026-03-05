<?php

namespace App\Services\Item\Item;

// モデル
use App\Models\Item;
// その他
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class ItemDownloadService
{
    // ダウンロードするデータを取得
    public function getDownloadData($items)
    {
        // チャンクサイズを指定
        $chunk_size = 1000;
        $response = new StreamedResponse(function () use ($items, $chunk_size){
            // ハンドルを取得
            $handle = fopen('php://output', 'wb');
            // BOMを書き込む
            fwrite($handle, "\xEF\xBB\xBF");
            // システムに定義してあるヘッダーを取得し、書き込む
            $header = Item::downloadHeader();
            fputcsv($handle, $header);
            // レコードをチャンクごとに書き込む
            $items->chunk($chunk_size, function ($items) use ($handle){
                // 商品の分だけループ処理
                foreach($items as $item){
                    // 変数に情報を格納
                    $row = [
                        $item->item_code,
                        $item->item_jan_code,
                        $item->item_name,
                        $item->item_category,
                        $item->model_jan_code,
                        $item->exp_start_position,
                        $item->lot_1_start_position,
                        $item->lot_1_length,
                        $item->lot_2_start_position,
                        $item->lot_2_length,
                        $item->s_power_code,
                        $item->s_power_code_start_position,
                        $item->is_stock_managed_text,
                        $item->sort_order,
                        $item->item_image_file_name === SystemEnum::DEFAULT_ITEM_IMAGE_FILE_NAME ? 'なし' : 'あり',
                        CarbonImmutable::parse($item->updated_at)->isoFormat('Y年MM月DD日(ddd) HH:mm:ss'),
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