<?php

namespace App\Services\Shipping\ReturnToShukkaMachi;

// モデル
use App\Models\Order;
use App\Models\ShippingGroup;
// 列挙
use App\Enums\OrderStatusEnum;
// その他
use Illuminate\Support\Facades\DB;

class ReturnToShukkaMachiService
{
    // 出荷待ちに戻せる受注であるか確認
    public function checkUpdatableReturnToShukkaMachi($chk)
    {
        $orders = Order::whereIn('order_control_id', $chk)->lockForUpdate()->get();
        // 受注ステータスが作業中以外の受注がある場合
        if($orders->contains(fn($order) => $order->order_status_id !== OrderStatusEnum::SAGYO_CHU)){
            throw new \RuntimeException('出荷待ちへ戻すことができない注文ステータスが含まれています。');
        }
        // 出荷検品が完了している受注がある場合
        if($orders->where('is_shipping_inspection_complete', 1)->count() > 0){
            throw new \RuntimeException('出荷検品が完了している受注が存在しています。');
        }
    }

    // 出荷待ちに戻す処理
    public function procReturnToShukkaMachi($chk)
    {
        // 受注ステータスを「出荷待ち」へ変更
        Order::whereIn('order_control_id', $chk)->update([
            'order_status_id' => OrderStatusEnum::SHUKKA_MACHI,
            'tracking_no' => null,
            'shipping_group_id' => null,
        ]);
        // 受注が紐付いていない出荷グループがあれば削除
        ShippingGroup::doesntHave('orders')->delete();
        return;
    }
}