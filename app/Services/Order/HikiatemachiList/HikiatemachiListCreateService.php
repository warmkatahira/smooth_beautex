<?php

namespace App\Services\Order\HikiatemachiList;

// モデル
use App\Models\Order;
// 列挙
use App\Enums\OrderStatusEnum;
// その他
use Illuminate\Support\Facades\DB;

class HikiatemachiListCreateService
{
    public function getCreateItem()
    {
        // 未引当数を商品毎に集計して取得
        $orders = Order::where('order_status_id', OrderStatusEnum::HIKIATE_MACHI)
                    ->with('order_items.item')
                    ->orderBy('order_import_date')
                    ->orderBy('order_import_time')
                    ->orderBy('order_control_id')
                    ->get();
        // 引当待ちの受注が無い場合
        if($orders->isEmpty()){
            throw new \RuntimeException('引当待ちの受注がありません。');
        }
        return $orders;
    }
}