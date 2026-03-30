<?php

namespace App\Services\Order\OrderItem;

// モデル
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
// 列挙
use App\Enums\OrderStatusEnum;

class OrderItemCreateService
{
    // 受注商品を追加できる注文であるか確認
    public function checkOrderItemCreatable($order_control_id)
    {
        // 受注を取得
        $order = Order::getSpecifyByOrderControlId($order_control_id)->lockForUpdate()->first();
        // 注文ステータスが「出荷待ち」以下ではない場合
        if($order->order_status_id > OrderStatusEnum::SHUKKA_MACHI){
            throw new \RuntimeException('受注商品を追加する場合、注文ステータスを「出荷待ち」以下にして下さい。');
        }
        return $order;
    }

    // 受注商品を追加
    public function createOrderItem($request)
    {
        // 商品を取得
        $item = Item::getSpecifyByItemCode($request->order_item_code)->first();
        // 受注商品を追加
        OrderItem::create([
            'order_control_id'      => $request->order_control_id,
            'order_item_code'       => $request->order_item_code,
            'order_item_name'       => $item->item_name,
            'shipping_quantity'     => $request->shipping_quantity,
            'unallocated_quantity'  => $request->shipping_quantity,
            'order_item_unit_price' => $request->order_item_unit_price,
        ]);
    }

    // 注文ステータスを変更
    public function updateOrderStatus($order)
    {
        // 現在の注文ステータスが「出荷待ち」の場合
        if($order->order_status_id == OrderStatusEnum::SHUKKA_MACHI){
            // 注文ステータスを「引当待ち」に変更
            $order->update([
                'order_status_id' => OrderStatusEnum::HIKIATE_MACHI,
            ]);
        }
    }
}