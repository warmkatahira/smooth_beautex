<?php

namespace App\Services\Item\ItemQrAnalysis;

// モデル
use App\Models\ItemQrAnalysisHistory;
use App\Models\Item;

class ItemQrAnalysisResultUpdateService
{
    // 商品QR解析履歴を取得
    public function getItemQrAnalysisHistory($request)
    {
        // 商品QR解析履歴を取得
        $item_qr_analysis_history = ItemQrAnalysisHistory::getSpecify($request->item_qr_analysis_history_id)->first();        
        // lot_start_position と lot_length が空の場合
        if(is_null($item_qr_analysis_history->lot_start_position) || is_null($item_qr_analysis_history->lot_length)){
            throw new \RuntimeException('LOT開始位置またはLOT桁数が解析されていないため、反映できませんでした。');
        }
        return $item_qr_analysis_history;
    }

    // 商品を取得
    public function getItem($item_qr_analysis_history)
    {
        // 商品を取得
        $item = Item::where('item_jan_code', $item_qr_analysis_history->jan_code)->first();
        if(!$item){
            throw new \RuntimeException('JANコードに一致する商品が見つかりませんでした。');
        }
        return $item;
    }

    // 商品QR解析結果を反映
    public function updateItemQrAnalysisResult($item, $item_qr_analysis_history)
    {
        // カラーIDを条件に設定を更新
        Item::where('color_id', $item->color_id)->update([
            'exp_start_position'    => 16,
            'lot_1_start_position'  => $item_qr_analysis_history->lot_start_position,
            'lot_1_length'          => $item_qr_analysis_history->lot_length,
        ]);
    }
}