<?php

namespace App\Services\Order\OrderDelete;

// モデル
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
// 列挙
use App\Enums\OrderStatusEnum;
// その他
use Illuminate\Support\Facades\DB;

class OrderDeleteService
{
    // 削除できる受注であるか確認
    public function checkDeletable($chk)
    {
        // 対象をロック
        $orders = Order::whereIn('order_control_id', $chk)->lockForUpdate()->get();
        $order_items = OrderItem::whereIn('order_control_id', $chk)->lockForUpdate()->get();
        // 注文ステータスが「出荷待ち」より大きい対象が存在する場合
        if($orders->where('order_status_id', '>', OrderStatusEnum::SHUKKA_MACHI)->count() > 0){
            throw new \RuntimeException('削除できない注文ステータスが含まれています。');
        }
    }

    // 受注を削除
    public function deleteOrder($chk)
    {
        // 受注を削除
        Order::whereIn('order_control_id', $chk)->delete();
    }
}