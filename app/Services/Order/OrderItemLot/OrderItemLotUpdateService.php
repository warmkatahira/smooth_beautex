<?php

namespace App\Services\Order\OrderItemLot;

// モデル
use App\Models\OrderItemLot;
// 列挙
use App\Enums\OrderStatusEnum;

class OrderItemLotUpdateService
{
    // 受注商品LOTを取得
    public function getOrderItemLot($lots)
    {
        // order_item_lot_idを取得
        $lot_ids = collect($lots)->pluck('order_item_lot_id');
        // 受注商品LOTを取得
        return OrderItemLot::with('order_item.order')
                                ->whereIn('order_item_lot_id', $lot_ids)
                                ->get()
                                ->keyBy('order_item_lot_id');
    }

    // 更新できる受注であるかチェック
    public function validateOrderItemLotEditable($order_item_lots)
    {
        // 受注商品LOTの分だけループ処理
        foreach($order_item_lots as $order_item_lot){
            // 紐づく受注を取得
            $order = $order_item_lot->order_item->order;
            // 注文ステータスが作業中かつ出荷検品完了でない場合は403を返す
            // （画面表示での制御だけでは直接リクエストを防げないため、サーバー側でも二重チェック）
            abort_if(
                $order->order_status_id !== OrderStatusEnum::SAGYO_CHU
                || $order->is_shipping_inspection_complete !== 1,
                403
            );
        }
    }

    // 受注商品LOTを更新
    public function updateOrderItemLot($request, $order_item_lots)
    {
        // 送信されてきたLOTの分だけループ処理
        foreach($request->lots as $lotData){
            // LOT/EXP/数量を更新
            $order_item_lots[$lotData['order_item_lot_id']]->update([
                'lot'       => $lotData['lot'] ?? null,
                'exp'       => $lotData['exp'] ?? null,
                'quantity'  => $lotData['quantity'],
            ]);
        }
    }
}