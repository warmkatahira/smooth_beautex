<?php

namespace App\Services\Stock\ReceivingInspection;

// モデル
use App\Models\Stock;
use App\Models\StockOperationHistory;
use App\Models\StockOperationHistoryDetail;
// その他
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
// 列挙
use App\Enums\StockCategoryEnum;

class ReceivingInspectionEnterService
{
    // stocksにレコードがない在庫を追加
    public function createNoStockRecord($request)
    {
        // item_idを抽出
        $item_ids = array_column(session('progress'), 'item_id');
        // item_idの分だけループ処理
        foreach($item_ids as $item_id){
            // 倉庫IDと商品IDを指定して在庫を取得
            $stock = Stock::getSpecifyByBaseIdItemId($request->base_id, $item_id)->first();
            // レコードが取得できていない場合
            if(is_null($stock)){
                // レコードを追加
                Stock::create([
                    'base_id' => $request->base_id,
                    'item_id' => $item_id,
                ]);
            }
        }
        // 操作対象の在庫をロック
        Stock::where(function ($query) use ($item_ids, $request) {
            foreach ($item_ids as $item_id) {
                $query->orWhere(function ($q) use ($item_id, $request) {
                    $q->where('base_id', $request->base_id)
                    ->where('item_id', $item_id);
                });
            }
        })
        ->lockForUpdate()
        ->get();
    }

    // 入庫対象の情報を配列に格納
    public function setArray($request)
    {
        // 入庫対象の情報を格納する配列を初期化
        $stock_update_arr = [];
        // 検品情報の分だけループ処理
        foreach(session('progress') as $progress){
            // 在庫を取得
            $stock = Stock::getSpecifyByBaseIdItemId($request->base_id, $progress['item_id'])->first();
            // 配列に入庫対象の情報を格納
            $stock_update_arr[] = [
                'stock_id' => $stock->stock_id,
                'quantity' => $progress['quantity'],
            ];
        }
        return $stock_update_arr;
    }
}