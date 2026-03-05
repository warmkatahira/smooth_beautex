<?php

namespace App\Services\Shipping\ShippingInspectionActualDelete;

// モデル
use App\Models\Order;
use App\Models\OrderItemLot;
// 列挙
use App\Enums\OrderStatusEnum;

class ShippingInspectionActualDeleteService
{
    // 出荷検品実績を削除できるか確認
    public function checkDeletable($order_control_id)
    {
        // 受注を取得
        $order = Order::getSpecifyByOrderControlId($order_control_id)->lockForUpdate()->first();
        // 出荷検品が完了していない場合
        if($order->is_shipping_inspection_complete === 0){
            throw new \RuntimeException('出荷検品が完了していない受注です。');
        }
        // 注文ステータスが「作業中」ではない場合
        if($order->order_status_id !== OrderStatusEnum::SAGYO_CHU){
            throw new \RuntimeException('出荷検品実績削除が実施できない注文ステータスです。');
        }
        return $order;
    }

    // 出荷検品実績を削除
    public function deleteShippingInspectionActual($order)
    {
        // 出荷検品関連のカラムを更新
        $order->update([
            'is_shipping_inspection_complete'   => 0,
            'shipping_inspection_date'          => null,
        ]);
        // order_item_idのリストを取得
        $order_item_ids = $order->order_items()->pluck('order_item_id');
        // Lot情報を削除
        OrderItemLot::whereIn('order_item_id', $order_item_ids)->delete();
    }
}