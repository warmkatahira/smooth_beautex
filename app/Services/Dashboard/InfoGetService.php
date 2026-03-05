<?php

namespace App\Services\Dashboard;

// モデル
use App\Models\Order;
// 列挙
use App\Enums\OrderStatusEnum;
// その他
use Carbon\CarbonImmutable;

class InfoGetService
{
    // 表示する情報を取得
    public function getInfo()
    {
        // 作業中の受注件数を取得
        $sagyo_chu_order_count = Order::getOrderSpecifyOrderStatus(OrderStatusEnum::SAGYO_CHU)->get()->count();
        // 当月の出荷件数を取得
        $current_month_shipped_count = Order::getShippedOrder(CarbonImmutable::now()->startOfMonth(), CarbonImmutable::now()->endOfMonth())->get()->count();
        // 当月の出荷数量を取得
        $current_month_shipped_quantity = Order::getShippedQuantity(CarbonImmutable::now()->startOfMonth(), CarbonImmutable::now()->endOfMonth());
        return compact('sagyo_chu_order_count', 'current_month_shipped_count', 'current_month_shipped_quantity');
    }
}