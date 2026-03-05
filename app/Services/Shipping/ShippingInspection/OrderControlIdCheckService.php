<?php

namespace App\Services\Shipping\ShippingInspection;

// モデル
use App\Models\Order;
// 列挙
use App\Enums\OrderStatusEnum;

class OrderControlIdCheckService
{
    // 出荷検品できる受注であるか確認
    public function check($order_control_id)
    {
        // 送信されてきた出荷指示番号でordersからレコードを取得
        $order = Order::getSpecifyByOrderControlId($order_control_id)->where('order_status_id', OrderStatusEnum::SAGYO_CHU)->first();
        // レコードが取得できているか
        if(!$order){
            return '受注が存在しません。';
        }
        // 出荷検品が未実施であるか
        if($order->is_shipping_inspection_complete === 1){
            return '出荷検品が完了しています。';
        }
        // 配送伝票番号が埋まっているか
        if(!$order->tracking_no){
            return '配送伝票番号が設定されていません。';
        }
        // 問題なければnullを返す
        return null;
    }
}