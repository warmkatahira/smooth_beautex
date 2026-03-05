<?php

namespace App\Services\Common;

// モデル
use App\Models\StockHistoryCategory;
use App\Models\StockHistory;
use App\Models\StockHistoryDetail;
// その他
use Illuminate\Support\Facades\Auth;

class StockHistoryCreateService
{
    // 在庫履歴に追加
    public function createStcokHistory($stock_history_category_name, $comment, $stock_update_arr)
    {
        // 在庫履歴区分を取得
        $stock_history_category = StockHistoryCategory::where('stock_history_category_name', $stock_history_category_name)->first();
        // 在庫履歴区分が取得できていない場合
        if(is_null($stock_history_category)){
            throw new \RuntimeException('在庫履歴区分が正しくありません。');
        }
        // 概要レコードを追加して取得
        $stock_history = StockHistory::create([
            'user_no' => Auth::user()->user_no,
            'stock_history_category_id' => $stock_history_category->stock_history_category_id,
            'comment' => $comment,
        ]);
        // レコード追加に使用する配列を初期化
        $create_stock_detail_arr = [];
        // 追加するレコードの分だけループ処理
        foreach($stock_update_arr as $stock_update){
            // 追加する情報を配列にセット
            $create_stock_detail_arr[] = [
                'stock_history_id' => $stock_history->stock_history_id,
                'stock_id' => $stock_update['stock_id'],
                'quantity' => $stock_update['quantity'],
            ];
        }
        // 詳細レコードを追加
        StockHistoryDetail::upsert($create_stock_detail_arr, 'history_detail_id');
    }
}