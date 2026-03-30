<?php

namespace App\Services\Stock\ReceivingInspection;

// モデル
use App\Models\Stock;

class ReceivingInspectionEnterService
{
    // stocksにレコードがない在庫を追加
    public function createNoStockRecord($request)
    {
        // 検品情報の分だけループ処理
        foreach(session('progress') as $key => $item){
            // 倉庫ID・商品ID・lot・expを指定して在庫を取得
            $stock = Stock::where('base_id', $request->base_id)
                            ->where('item_id', $item['item_id'])
                            ->where('lot', $item['lot'])
                            ->where('exp', $item['exp'])
                            ->first();
            // レコードが取得できていない場合
            if(is_null($stock)){
                // レコードを追加
                Stock::create([
                    'base_id' => $request->base_id,
                    'item_id' => $item['item_id'],
                    'lot'     => $item['lot'],
                    'exp'     => $item['exp'],
                ]);
            }
        }
        // 操作対象の在庫をロック
        Stock::where(function ($query) use ($request) {
            foreach(session('progress') as $item){
                $query->orWhere(function ($q) use ($item, $request) {
                    $q->where('base_id', $request->base_id)
                        ->where('item_id', $item['item_id'])
                        ->where('lot', $item['lot'])
                        ->where('exp', $item['exp']);
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
        foreach(session('progress') as $item){
            // 倉庫ID・商品ID・lot・expを指定して在庫を取得
            $stock = Stock::where('base_id', $request->base_id)
                            ->where('item_id', $item['item_id'])
                            ->where('lot', $item['lot'])
                            ->where('exp', $item['exp'])
                            ->first();
            // 配列に追加
            $stock_update_arr[] = [
                'stock_id' => $stock->stock_id,
                'quantity' => $item['quantity'],
            ];
        }
        return $stock_update_arr;
    }
}