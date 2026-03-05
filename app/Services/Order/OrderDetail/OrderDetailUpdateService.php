<?php

namespace App\Services\Order\OrderDetail;

// モデル
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
// 列挙
use App\Enums\OrderStatusEnum;
// その他
use Illuminate\Support\Facades\DB;

class OrderDetailUpdateService
{
    // 受注をロックして取得
    public function getOrder($request)
    {
        // 対象をロック
        $order = Order::getSpecifyByOrderControlId($request->order_control_id)->lockForUpdate()->first();
        $order_items = OrderItem::where('order_control_id', $request->order_control_id)->lockForUpdate()->get();
        return $order;
    }

    // 出荷倉庫を更新できるか確認
    public function checkUpdatableShippingBase($order)
    {
        // 注文ステータスが「出荷待ち」よりも大きい場合
        if($order->order_status_id > OrderStatusEnum::SHUKKA_MACHI){
            throw new \RuntimeException('出荷倉庫を更新できない注文ステータスです。');
        }
    }

    // 引当済みの在庫数を戻す
    public function incrementAllocatedStockBackToAvailableStock($order)
    {
        // 在庫をロック
        Order::getSpecifyByOrderControlId($order->order_control_id)
                ->join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                ->join('items', 'items.item_code', 'order_items.order_item_code')
                ->join('stocks', 'stocks.item_id', 'items.item_id')
                ->where('stocks.base_id', $order->shipping_base_id)
                ->lockForUpdate()
                ->get();
        // order_itemsの分だけループ処理
        foreach($order->order_items as $order_item){
            // 引当済みの在庫数を有効在庫数に加算（戻す）
            Stock::where('base_id', $order->shipping_base_id)
                ->where('item_id', $order_item->item->item_id)
                ->increment('available_stock', ($order_item->order_quantity - $order_item->unallocated_quantity));
            // 在庫引当状態を初期化
            $order_item->update([
                'is_stock_allocated'    => 0,
                'unallocated_quantity'  => DB::raw("order_quantity"),
            ]);
            // 在庫引当状態を初期化
            $order_item->update([
                'is_stock_allocated'    => 0,
            ]);
        }
        return;
    }

    // 出荷倉庫を更新
    public function updateShippingBase($request, $order)
    {
        // 引当状態と出荷倉庫を更新
        $order->update([
            'is_allocated'      => 0,
            'shipping_base_id'  => $request->shipping_base_id,
        ]);
        return;
    }

    // 配送方法を更新できるか確認
    public function checkUpdatableShippingMethod($order)
    {
        // 注文ステータスが「作業中」よりも大きい場合
        if($order->order_status_id > OrderStatusEnum::SAGYO_CHU){
            throw new \RuntimeException('配送方法を更新できない注文ステータスです。');
        }
    }

    // 配送方法を更新
    public function updateShippingMethod($request, $order)
    {
        // 配送方法と配送伝票番号(Nullへ)を更新
        $order->update([
            'shipping_method_id'    => $request->shipping_method_id,
            'tracking_no'           => null,
        ]);
        return;
    }

    // 配送伝票番号を更新できるか確認
    public function checkUpdatableTrackingNo($order)
    {
        // 注文ステータスが「作業中」よりも大きい場合
        if($order->order_status_id > OrderStatusEnum::SAGYO_CHU){
            throw new \RuntimeException('配送伝票番号を更新できない注文ステータスです。');
        }
    }

    // 配送伝票番号を更新
    public function updateTrackingNo($request, $order)
    {
        // 配送伝票番号を更新
        $order->update([
            'tracking_no' => $request->tracking_no,
        ]);
        return;
    }

    // 受注メモを更新できるか確認
    public function checkUpdatableOrderMemo($order)
    {
        // 注文ステータスが「作業中」よりも大きい場合
        if($order->order_status_id > OrderStatusEnum::SAGYO_CHU){
            throw new \RuntimeException('受注メモを更新できない注文ステータスです。');
        }
    }

    // 受注メモを更新
    public function updateOrderMemo($request, $order)
    {
        // 受注メモを更新
        $order->update([
            'order_memo' => $request->order_memo,
        ]);
        return;
    }

    // 出荷作業メモを更新できるか確認
    public function checkUpdatableShippingWorkMemo($order)
    {
        // 注文ステータスが「作業中」よりも大きい場合
        if($order->order_status_id > OrderStatusEnum::SAGYO_CHU){
            throw new \RuntimeException('出荷作業メモを更新できない注文ステータスです。');
        }
    }

    // 出荷作業メモを更新
    public function updateShippingWorkMemo($request, $order)
    {
        // 出荷作業メモを更新
        $order->update([
            'shipping_work_memo' => $request->shipping_work_memo,
        ]);
        return;
    }

    // 配送希望日を更新できるか確認
    public function checkUpdatableDesiredDeliveryDate($order)
    {
        // 注文ステータスが「作業中」よりも大きい場合
        if($order->order_status_id > OrderStatusEnum::SAGYO_CHU){
            throw new \RuntimeException('配送希望日を更新できない注文ステータスです。');
        }
    }

    // 配送希望日を更新
    public function updateDesiredDeliveryDate($request, $order)
    {
        // 配送希望日を更新
        $order->update([
            'desired_delivery_date' => $request->desired_delivery_date,
        ]);
        return;
    }
}