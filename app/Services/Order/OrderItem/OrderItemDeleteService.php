<?php

namespace App\Services\Order\OrderItem;

// モデル
use App\Models\Order;
use App\Models\OrderItem;
// 列挙
use App\Enums\OrderStatusEnum;

class OrderItemDeleteService
{
    // 受注商品を削除できる注文であるか確認
    public function checkOrderItemDeletable($order_item_id)
    {
        // 削除対象の受注商品を取得
        $order_item = OrderItem::getSpecify($order_item_id)->lockForUpdate()->first();
        // 削除対象の受注商品の受注を取得
        $order = Order::getSpecifyByOrderControlId($order_item->order_control_id)->lockForUpdate()->first();
        // 注文ステータスが「作業中」以下ではない場合
        if($order->order_status_id > OrderStatusEnum::SAGYO_CHU){
            throw new \RuntimeException('受注商品を削除する場合、注文ステータスを「作業中」以下にして下さい。');
        }
        // 出荷検品が完了している場合
        if($order->is_shipping_inspection_complete){
            throw new \RuntimeException('受注商品を削除する場合、出荷検品を未検品状態にして下さい。');
        }
        return $order_item;
    }

    // 受注商品を削除
    public function deleteOrderItem($order_item)
    {
        // 商品を削除
        $order_item->delete();
    }
}