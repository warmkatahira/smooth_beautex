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
    public function getInfo($from, $to)
    {
        // 作業前の注文件数を取得
        $sagyo_mae_order_count = Order::getOrderSpecifyOrderStatus('<=', OrderStatusEnum::SHUKKA_MACHI)->get()->count();
        // 作業前の注文の出荷数量を取得
        $orders = Order::getOrderSpecifyOrderStatus('<=', OrderStatusEnum::SHUKKA_MACHI)->with('order_items')->get();
        $sagyo_mae_ship_quantity = $orders->flatMap(function($order) {
                                        return $order->order_items;
                                    })->sum('order_quantity');
        // 作業中の注文件数を取得
        $sagyo_chu_order_count = Order::getOrderSpecifyOrderStatus('=', OrderStatusEnum::SAGYO_CHU)->get()->count();
        // 作業中の注文の出荷数量を取得
        $orders = Order::getOrderSpecifyOrderStatus('=', OrderStatusEnum::SAGYO_CHU)->with('order_items')->get();
        $sagyo_chu_ship_quantity = $orders->flatMap(function($order) {
                                        return $order->order_items;
                                    })->sum('order_quantity');
        // 当月の出荷件数を取得
        $month_shipped_count = Order::getShippedOrder($from, $to)->get()->count();
        // 当月の出荷数量を取得
        $month_shipped_quantity = Order::getShippedQuantity($from, $to);
        return compact('sagyo_mae_order_count', 'sagyo_mae_ship_quantity', 'sagyo_chu_order_count', 'sagyo_chu_ship_quantity', 'month_shipped_count', 'month_shipped_quantity');
    }
}