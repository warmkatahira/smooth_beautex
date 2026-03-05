<?php

namespace App\Services\Shipping\ShippingInspection;

// モデル
use App\Models\Order;
use App\Models\OrderItemLot;
// その他
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\OrderStatusEnum;

class CompleteService
{
    // ordersテーブルを更新
    public function updateInspectionResultForOrder($order_control_id)
    {
        // 受注を取得してロック
        $order = Order::getSpecifyByOrderControlId($order_control_id)->where('order_status_id', OrderStatusEnum::SAGYO_CHU)->lockForUpdate()->first();
        // 受注が取得できていない場合
        if(!$order){
            throw new \RuntimeException('出荷検品が正常に完了できませんでした。');
        }
        // 出荷検品と出荷検品日時を更新
        $order->update([
            'is_shipping_inspection_complete' => 1,
            'shipping_inspection_date' => CarbonImmutable::now(),
        ]);
        return;
    }

    // order_item_lotsテーブルを更新
    public function updateInspectionResultForOrderItemLot()
    {
        // LOT情報があれば処理を行う
        if(!empty(session('lot_result'))){
            // セッションの中身を配列にセット
            $lot_result = session('lot_result');
            // 整理する配列をセット
            $insert_lot = [];
            // 配列の分だけループ処理
            foreach($lot_result as $lot){
                foreach($lot as $value){
                    $param = [
                        'order_item_id' => $value['order_item_id'],
                        'lot' => $value['lot'],
                        'quantity' => $value['quantity'],
                    ];
                    $insert_lot[] = $param;
                }
            }
            // テーブルへ追加
            OrderItemLot::upsert($insert_lot, 'order_item_lot_id');
        }
        return;
    }
}