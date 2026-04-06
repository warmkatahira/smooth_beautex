<?php

namespace App\Services\Order\OrderItem;

// モデル
use App\Models\Order;
use App\Models\OrderItem;
// 列挙
use App\Enums\OrderStatusEnum;

class PastOrderItemService
{
    // 現在の注文の商品を物理削除
    public function deleteCurrentOrderItem($current_order_control_id)
    {
        // 現在の注文の商品を物理削除
        OrderItem::where('order_control_id', $current_order_control_id)->delete();
    }

    // 商品情報を引用
    public function referenceOrderItem($request)
    {
        // 過去注文の商品を取得
        $past_order_items = OrderItem::where('order_control_id', $request->past_order_control_id)->get();
        // 現在の注文にコピー
        $new_items = $past_order_items->map(function($item) use ($request){
            $arr = $item->toArray();
            unset($arr['order_item_id']);
            $arr['order_control_id']        = $request->current_order_control_id;
            $arr['is_item_allocated']       = 0;
            $arr['is_stock_allocated']      = 0;
            $arr['unallocated_quantity']    = $item->shipping_quantity;
            $arr['created_at']              = now();
            $arr['updated_at']              = now();
            return $arr;
        })->toArray();
        // 追加
        OrderItem::insert($new_items);
        // 注文ステータスを「確認待ち」に変更し、引当済みを「0」に更新
        Order::getSpecifyByOrderControlId($request->current_order_control_id)->update([
            'order_status_id'   => OrderStatusEnum::KAKUNIN_MACHI,
            'is_allocated'      => 0,
        ]);
    }
}