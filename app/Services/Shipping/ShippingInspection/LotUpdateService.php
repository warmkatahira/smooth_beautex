<?php

namespace App\Services\Shipping\ShippingInspection;

// 列挙
use App\Enums\OrderStatusEnum;

class LotUpdateService
{
    // Lotの配列を更新
    public function updateLotResult($lot, $exp)
    {
        // セッションの中身を配列にセット
        $lot_result = session('lot_result', []);
        // キーで使用する情報を変数に格納
        $order_item_id = session('order_item_id');
        $lot_exp_key = $lot . '-' . $exp;
        // 検品対象商品の配列がない場合は初期化
        if(!array_key_exists($order_item_id, $lot_result)){
            $lot_result[$order_item_id] = [];
        }
        // 同じLOT×EXPの組み合わせが存在すれば数量を+1、なければ追加
        if(array_key_exists($lot_exp_key, $lot_result[$order_item_id])){
            $lot_result[$order_item_id][$lot_exp_key]['quantity']++;
        }else{
            $lot_result = $this->insertLotResult($lot_result, $lot, $exp, $order_item_id, $lot_exp_key);
        }
        // セッションへ戻す
        session(['lot_result' => $lot_result]);
    }

    // 配列へLOTを追加
    public function insertLotResult($lot_result, $lot, $exp, $order_item_id, $lot_exp_key)
    {
        $lot_result[$order_item_id][$lot_exp_key] = [
            'order_item_id'     => $order_item_id,
            'item_id'           => session('item_id'),
            'lot'               => $lot,
            'exp'               => $exp,
            'quantity'          => 1,
        ];
        return $lot_result;
    }
}