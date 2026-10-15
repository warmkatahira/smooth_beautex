<?php

namespace App\Services\Order\OrderItem;

// モデル
use App\Models\Order;
use App\Models\OrderItem;
// 列挙
use App\Enums\OrderStatusEnum;

class OrderItemAllocateReleaseService
{
    // 引当解除できる受注商品であるか確認
    public function checkAllocateReleasable($order_item_id)
    {
        // 引当解除対象の受注商品を取得
        $order_item = OrderItem::getSpecify($order_item_id)->lockForUpdate()->first();
        // 引当解除対象の受注商品の受注を取得
        $order = Order::getSpecifyByOrderControlId($order_item->order_control_id)->lockForUpdate()->first();
        // 注文ステータスが「出荷待ち」以下ではない場合
        if($order->order_status_id > OrderStatusEnum::SHUKKA_MACHI){
            throw new \RuntimeException('引当解除する場合、注文ステータスを「出荷待ち」以下にして下さい。');
        }
        // 引当されている数量がない場合
        if($order_item->shipping_quantity - $order_item->unallocated_quantity <= 0){
            throw new \RuntimeException('引当されている数量がありません。');
        }
        return [$order, $order_item];
    }

    // 引当解除
    public function releaseAllocate($order, $order_item)
    {
        // 受注商品の引当を解除
        $order_item->update([
            'is_stock_allocated'   => 0,
            'unallocated_quantity' => $order_item->shipping_quantity,
        ]);
        // 注文ステータスが「出荷待ち」の場合のみ、「引当待ち」に戻す
        if($order->order_status_id == OrderStatusEnum::SHUKKA_MACHI){
            $order->update([
                'is_allocated'      => 0,
                'order_status_id'   => OrderStatusEnum::HIKIATE_MACHI,
            ]);
        }
    }
}
