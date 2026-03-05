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

    // 引当済みの在庫数を戻す
    public function incrementAllocatedStockBackToAvailableStock($chk)
    {
        // 削除対象で在庫が引当済みになっている商品を取得(在庫管理を行っている商品のみ)
        $allocated_items = Order::whereIn('orders.order_control_id', $chk)
                            ->join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                            ->join('items', 'items.item_code', 'order_items.order_item_code')
                            ->where('is_stock_managed', 1)
                            ->select(DB::raw("sum(order_items.order_quantity - order_items.unallocated_quantity) as total_allocated_quantity, shipping_base_id, items.item_id"))
                            ->groupBy('shipping_base_id', 'items.item_id')
                            ->having('total_allocated_quantity', '>', 0)
                            ->get();
        // 引当済みの商品の分だけループ処理
        $stocks = Stock::where(function ($query) use ($allocated_items) {
            foreach($allocated_items as $index => $allocated_item){
                // インデックスによってメソッドを可変
                $queryMethod = $index === 0 ? 'where' : 'orWhere';
                // 条件を適用
                $query->$queryMethod(function ($subQuery) use ($allocated_item) {
                    $subQuery->where('base_id', $allocated_item->shipping_base_id)
                            ->where('item_id', $allocated_item->item_id);
                });
            }
        });
        // 在庫をロック
        $stocks = $stocks->lockForUpdate()->get();
        // 引当済みの商品の分だけループ処理
        foreach($allocated_items as $allocated_item){
            // 引当済みの在庫数を有効在庫数に加算（戻す）
            Stock::where('base_id', $allocated_item->shipping_base_id)
                    ->where('item_id', $allocated_item->item_id)
                    ->increment('available_stock', ($allocated_item->total_allocated_quantity));
        }
    }

    // 受注を削除
    public function deleteOrder($chk)
    {
        // 受注を削除
        Order::whereIn('order_control_id', $chk)->delete();
    }
}