<?php

namespace App\Services\Stock\InputStockOperation;

use App\Models\Stock;

class InputStockOperationEnterService
{
    // 在庫操作するデータを取得
    public function getOperationData($quantity)
    {
        // 倉庫の分だけループ処理
        foreach($quantity as $base_id => $arr){
            // 値がnull又は0の要素を取り除く
            $quantity[$base_id] = array_filter($arr);
        }
        // 配列からnullの要素を取り除く
        $quantity = array_filter($quantity);
        // 在庫操作する内容を格納する配列を初期化
        $stock_update_arr = [];
        // 倉庫の分だけループ処理
        foreach($quantity as $base_id => $arr){
            // 商品の分だけループ処理
            foreach($arr as $item_id => $quantity){
                // 配列に情報を格納
                $stock_update_arr[] = [
                    'base_id' => $base_id,
                    'item_id' => $item_id,
                    'quantity' => $quantity,
                ];
            }
        }
        return $stock_update_arr;
    }

    // stocksにレコードがない在庫を追加
    public function createNoStockRecord($stock_update_arr)
    {
        // 在庫操作する分だけループ処理
        foreach($stock_update_arr as $stock_update){
            // 倉庫IDと商品IDを指定して在庫を取得
            $stock = Stock::getSpecifyByBaseIdItemId($stock_update['base_id'], $stock_update['item_id'])->first();
            // レコードが取得できていない場合
            if(is_null($stock)){
                // レコードを追加
                Stock::create([
                    'base_id' => $stock_update['base_id'],
                    'item_id' => $stock_update['item_id'],
                ]);
            }
        }
        // 操作対象の在庫をロック
        Stock::where(function ($query) use ($stock_update_arr) {
            foreach ($stock_update_arr as $condition) {
                $query->orWhere(function ($q) use ($condition) {
                    $q->where('base_id', $condition['base_id'])
                    ->where('item_id', $condition['item_id']);
                });
            }
        })
        ->lockForUpdate()
        ->get();
    }

    // 在庫操作するデータを整理
    public function updateStockUpdateArr($stock_update_arr)
    {
        // 整理後に情報を格納する配列を初期化
        $arr = [];
        // 在庫操作する分だけループ処理
        foreach($stock_update_arr as $stock_update){
            // 倉庫IDと商品IDを指定して在庫を取得
            $stock = Stock::getSpecifyByBaseIdItemId($stock_update['base_id'], $stock_update['item_id'])->first();
            // 配列に情報を格納
            $arr[] = [
                'stock_id' => $stock->stock_id,
                'quantity' => $stock_update['quantity'],
            ];
        }
        return $arr;
    }

    // 在庫操作できる内容か確認
    public function check($stock_update_arr)
    {
        // 在庫操作対象の分だけループ処理
        foreach($stock_update_arr as $stock_update){
            // 在庫を取得
            $stock = Stock::getSpecify($stock_update['stock_id'])->with('base')->with('item')->first();
            // 数量がマイナスの場合
            if($stock_update['quantity'] < 0){
                // 全在庫数がマイナスになる数量ではないか(絶対値で比較している)
                if($stock->total_stock < abs($stock_update['quantity'])){
                    throw new \RuntimeException("全在庫数がマイナスになる数量が入力されている箇所があります。\n".
                                                "倉庫名:".$stock->base->base_name."\n".
                                                "商品コード：".$stock->item->item_code."\n".
                                                "商品名：".$stock->item->item_name
                                            );
                }
                // 有効在庫数がマイナスになる数量ではないか(絶対値で比較している)
                if($stock->available_stock < abs($stock_update['quantity'])){
                    throw new \RuntimeException("有効在庫数がマイナスになる数量が入力されている箇所があります。\n".
                                                "倉庫名:".$stock->base->base_name."\n".
                                                "商品コード：".$stock->item->item_code."\n".
                                                "商品名：".$stock->item->item_name
                                            );
                }
            }
        }
    }
}