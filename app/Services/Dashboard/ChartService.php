<?php

namespace App\Services\Dashboard;

// モデル
use App\Models\Order;
// 列挙
use App\Enums\OrderStatusEnum;
// その他
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class ChartService
{
    // グラフ表示する期間を取得
    public function getPeriod()
    {
        // 当月の月初と月末の日付を取得
        $from = CarbonImmutable::now()->startOfMonth()->toDateString();
        $to = CarbonImmutable::now()->endOfMonth()->toDateString();
        return compact('from', 'to');
    }

    // 期間内の日付を取得
    public function getDates($from, $to)
    {
        // 期間内の日付を格納する配列を初期化
        $dates = [];
        // 期間内の最初の日付をインスタンス化
        $current = CarbonImmutable::parse($from);
        // 曜日の日本語配列を定義（0:日曜 ～ 6:土曜）
        $weekdays = ['日', '月', '火', '水', '木', '金', '土'];
        // $toの日付になるまでループ処理
        while($current->lte($to)){
            // 曜日番号から日本語の曜日を取得
            $wday = $weekdays[$current->dayOfWeek];
            // 配列に日付を格納
            $dates[$current->toDateString()] = $current->format('m月d日') . "({$wday})";
            // 現在の日付に+1する
            $current = $current->addDay();
        }
        return $dates;
    }

    // 期間内の日別の出荷件数を取得
    public function getShippingCount($from, $to)
    {
        return Order::selectRaw('DATE(shipping_date) as date, COUNT(*) as count')
                    ->whereDate('shipping_date', '>=', $from)
                    ->whereDate('shipping_date', '<=', $to)
                    ->groupBy(DB::raw('DATE(shipping_date)'))
                    ->orderBy('date')
                    ->get()
                    ->keyBy('date');
    }

    // 期間内の日別の出荷数量を取得
    public function getShippingQuantity($from, $to)
    {
        return Order::join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                    ->selectRaw('DATE(orders.shipping_date) as date, SUM(order_items.order_quantity) as total_quantity')
                    ->whereDate('orders.shipping_date', '>=', $from)
                    ->whereDate('orders.shipping_date', '<=', $to)
                    ->groupBy(DB::raw('DATE(orders.shipping_date)'))
                    ->orderBy('date')
                    ->get()
                    ->keyBy('date');
    }
}