<?php

namespace App\Services\Shipping\TotalPickingList;

// モデル
use App\Models\Order;
use App\Models\Item;
use App\Models\ShippingGroup;
// その他
use Illuminate\Support\Facades\DB;

class TotalPickingListCreateService
{
    public function getCreateItem()
    {
        // 出荷グループを取得
        $shipping_group = ShippingGroup::getSpecify(session('search_shipping_group_id'))->first();
        // 残数計算で使用する全在庫数から引く出荷数を取得
        $shipping_group_order_quantity = ShippingGroup::join('orders', 'orders.shipping_group_id', 'shipping_groups.shipping_group_id')
                                            ->join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                                            ->join('items', 'items.item_code', 'order_items.order_item_code')
                                            ->where('shipping_groups.shipping_base_id', $shipping_group->shipping_base_id)
                                            ->where('estimated_shipping_date', '<=', $shipping_group->estimated_shipping_date)
                                            ->select(
                                                'shipping_groups.shipping_base_id',
                                                'item_id',
                                                DB::raw("SUM(order_items.order_quantity) as shipping_group_order_quantity"
                                            ))
                                            ->groupBy('shipping_groups.shipping_base_id', 'item_id');
        // トータルピックする出荷数を取得
        $order_quantities = Order::join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                                ->join('items', 'items.item_code', 'order_items.order_item_code')
                                ->where('shipping_group_id', session('search_shipping_group_id'))
                                ->select(
                                    'shipping_base_id',
                                    'item_id',
                                    DB::raw('SUM(order_items.order_quantity) as total_order_quantity')
                                )
                                ->groupBy('shipping_base_id', 'item_id');
        // 各情報を結合して表示する情報を取得
        $items = Item::joinSub($order_quantities, 'order_quantities', function ($join) {
                            $join->on('items.item_id', '=', 'order_quantities.item_id');
                        })
                        ->join('stocks', 'stocks.item_id', 'items.item_id')
                        ->joinSub($shipping_group_order_quantity, 'shipping_group_order_quantity', function ($join) {
                            $join->on('stocks.item_id', '=', 'shipping_group_order_quantity.item_id')
                                ->on('stocks.base_id', '=', 'shipping_group_order_quantity.shipping_base_id');
                        })
                        ->select(
                            'items.item_code',
                            'items.item_jan_code',
                            'items.item_name',
                            'stocks.item_location',
                            'total_order_quantity',
                            DB::raw("stocks.total_stock - COALESCE(shipping_group_order_quantity.shipping_group_order_quantity, 0) as remaining_stock")
                        )
                        ->groupBy('items.item_code', 'items.item_jan_code', 'items.item_name', 'stocks.item_location', 'stocks.total_stock', 'total_order_quantity', 'shipping_group_order_quantity.shipping_group_order_quantity')
                        ->orderBy('stocks.item_location', 'asc')
                        ->orderBy('items.item_code', 'asc')
                        ->get();
        // トータルの合計数を取得
        $report_total_order_quantity = $items->sum('total_order_quantity');
        // 合計出荷数が0の場合
        if($report_total_order_quantity === 0){
            throw new \RuntimeException('トータルピッキングリストが作成できません。');
        }
        return compact('items', 'report_total_order_quantity');
    }
}