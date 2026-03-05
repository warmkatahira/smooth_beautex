<?php

namespace App\Services\Shipping\ShippingInspection;

// モデル
use App\Models\Order;
// サービス
use App\Services\Shipping\ShippingInspection\ItemIdCodeCheckService;
// 列挙
use App\Enums\OrderStatusEnum;

class LotUpdateService
{
    // Lotの配列を更新
    public function updateLotResult($lot, $order_control_id)
    {
        // 受注管理IDを取得
        $order_control_id = Order::getSpecifyByOrderControlId($order_control_id)->where('order_status_id', OrderStatusEnum::SAGYO_CHU)->first()->order_control_id;
        // セッションの中身を配列にセット
        $lot_result = session('lot_result');
        // 検品対象商品の配列がある場合
        if(array_key_exists(session('order_item_id'), $lot_result)) {
            // 同じLOTが存在したか判定する変数
            $exsits = false;
            // 配列の分だけループ処理
            foreach($lot_result[session('order_item_id')] as $key => $value){
                // 同じLOTが存在すれば、数量を+1する
                if($value['lot'] == $lot){
                    $exsits = true;
                    $lot_result[session('order_item_id')][$key]['quantity'] = (int)$lot_result[session('order_item_id')][$key]['quantity'] + 1;
                    break;
                }
            }
            // 同じLOTが無ければ、配列に追加する
            if(!$exsits){
                $lot_result = $this->insertLotResult($lot_result, $lot, $order_control_id);
            }
        }
        // 検品対象商品の配列がない場合
        if(!array_key_exists(session('order_item_id'), $lot_result)) {
            $lot_result[session('order_item_id')] = [];
            $lot_result = $this->insertLotResult($lot_result, $lot, $order_control_id);
        }
        // セッションへ戻す
        session(['lot_result' => $lot_result]);
    }

    // 配列へLOTを追加
    public function insertLotResult($lot_result, $lot, $order_control_id)
    {
        array_push($lot_result[session('order_item_id')], [
            'order_control_id' => $order_control_id,
            'order_item_id' => session('order_item_id'),
            'item_id' => session('item_id'),
            'lot' => $lot,
            'quantity' => 1,
        ]);
        return $lot_result;
    }
}