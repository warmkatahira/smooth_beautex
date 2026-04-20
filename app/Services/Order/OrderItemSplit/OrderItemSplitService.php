<?php

namespace App\Services\Order\OrderItemSplit;

// モデル
use App\Models\Order;
use App\Models\OrderItem;

class OrderItemSplitService
{
    // 指定された受注商品を分割
    public function splitOrderItem($request)
    {
        // 対象の受注商品を取得
        $order_item = OrderItem::getSpecify($request->order_item_id)->lockForUpdate()->first();
        // 分割数量の配列を取得（例：[8, 8, 4]）
        $quantities = $request->quantities;
        // 先頭の数量で元レコードを更新
        $order_item->shipping_quantity = $quantities[0];
        // 1個口料金オーバーフラグをリセット
        $order_item->is_over_threshold = false;
        $order_item->save();
        // 2件目以降は元レコードを複製して新レコードとして追加
        foreach(array_slice($quantities, 1) as $quantity){
            // 元レコードを複製
            $new = $order_item->replicate();
            // 分割数量をセット
            $new->shipping_quantity = $quantity;
            // 1個口料金オーバーフラグをリセット
            $new->is_over_threshold = false;
            $new->save();
        }
        // 次の処理でorderを使うので、取得して返す(1件しか取得されないが、あえてget()している)
        return Order::getSpecifyByOrderControlId($order_item->order_control_id)->with('order_items')->lockForUpdate()->get();
    }
}