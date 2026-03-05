<?php

namespace App\Services\Order\ShippingWorkStart;

// モデル
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingGroup;
// 列挙
use App\Enums\OrderStatusEnum;

class ShippingWorkStartService
{
    // 選択している対象が出荷開始できるか確認
    public function checkShippingWorkStartable($chk)
    {
        // 対象をロック
        $orders = Order::whereIn('order_control_id', $chk)->lockForUpdate()->get();
        $order_items = OrderItem::whereIn('order_control_id', $chk)->lockForUpdate()->get();
        // 注文ステータスが「出荷待ち」以外の対象が存在する場合
        if($orders->where('order_status_id', '!=', OrderStatusEnum::SHUKKA_MACHI)->count() > 0){
            throw new \RuntimeException('注文ステータスが出荷待ち以外の受注が選択されています。');
        }
        // 複数の出荷倉庫が存在する場合
        if($orders->pluck('shipping_base_id')->unique()->count() > 1){
            throw new \RuntimeException('複数の出荷倉庫の受注が選択されています。');
        }
    }

    // 出荷グループを作成
    public function createShippingGroup($request)
    {
        // 出荷倉庫IDを取得するために、先頭のパラメータで受注を取得
        $order = Order::getSpecifyByOrderControlId($request->chk[0])->first();
        // 出荷グループを作成
        return ShippingGroup::create([
            'shipping_group_name'       => $request->shipping_group_name,
            'shipping_base_id'          => $order->shipping_base_id,
            'estimated_shipping_date'   => $request->estimated_shipping_date,
        ]);
    }

    // 出荷グループと注文ステータスを更新
    public function updateShippingWorkStart($chk, $shipping_group_id)
    {
        // 出荷グループと受注ステータスを更新
        return Order::whereIn('order_control_id', $chk)->update([
            'order_status_id'       => OrderStatusEnum::SAGYO_CHU,
            'shipping_group_id'     => $shipping_group_id,
        ]);
    }
}