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
        $shipping_group = ShippingGroup::getSpecify(session('filter_shipping_group_id'))->first();
        // 残数計算で使用する在庫数から引く出荷数を取得
        $shipping_group_shipping_quantity = ShippingGroup::join('orders', 'orders.shipping_group_id', 'shipping_groups.shipping_group_id')
                                            ->join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                                            ->join('items', 'items.item_id', 'order_items.item_id')
                                            ->where('shipping_groups.shipping_base_id', $shipping_group->shipping_base_id)
                                            ->where('estimated_shipping_date', '<=', $shipping_group->estimated_shipping_date)
                                            ->select(
                                                'shipping_groups.shipping_base_id',
                                                'order_items.item_id',
                                                DB::raw("SUM(order_items.shipping_quantity) as shipping_group_shipping_quantity"
                                            ))
                                            ->groupBy('shipping_groups.shipping_base_id', 'order_items.item_id');
        // トータルピックする出荷数を取得
        $order_quantities = Order::join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                                ->join('items', 'items.item_id', 'order_items.item_id')
                                ->where('shipping_group_id', session('filter_shipping_group_id'))
                                ->select(
                                    'shipping_base_id',
                                    'order_items.item_id',
                                    DB::raw('SUM(order_items.shipping_quantity) as total_shipping_quantity')
                                )
                                ->groupBy('shipping_base_id', 'order_items.item_id');
        // stocksをitem_id×base_id単位で合計するサブクエリ
        $stock_totals = DB::table('stocks')
                            ->select(
                                'stocks.item_id',
                                'base_id',
                                'item_location',
                                DB::raw('SUM(total_stock) as total_stock')
                            )
                            ->groupBy('stocks.item_id', 'base_id', 'item_location');
        // 各情報を結合して表示する情報を取得
        $items = Item::joinSub($order_quantities, 'order_quantities', function ($join) {
                            $join->on('items.item_id', '=', 'order_quantities.item_id');
                        })
                        ->joinSub($stock_totals, 'stock_totals', function ($join) {
                            $join->on('items.item_id', '=', 'stock_totals.item_id')
                                ->on('order_quantities.shipping_base_id', '=', 'stock_totals.base_id');
                        })
                        ->joinSub($shipping_group_shipping_quantity, 'shipping_group_shipping_quantity', function ($join) {
                            $join->on('stock_totals.item_id', '=', 'shipping_group_shipping_quantity.item_id')
                                ->on('stock_totals.base_id', '=', 'shipping_group_shipping_quantity.shipping_base_id');
                        })
                        ->select(
                            'items.item_code',
                            'items.item_jan_code',
                            'items.item_name',
                            'stock_totals.item_location',
                            'total_shipping_quantity',
                            DB::raw("stock_totals.total_stock - COALESCE(shipping_group_shipping_quantity.shipping_group_shipping_quantity, 0) as remaining_stock")
                        )
                        ->groupBy('items.item_code', 'items.item_jan_code', 'items.item_name', 'stock_totals.item_location', 'stock_totals.total_stock', 'total_shipping_quantity', 'shipping_group_shipping_quantity.shipping_group_shipping_quantity')
                        ->orderBy('stock_totals.item_location', 'asc')
                        ->orderBy('items.item_code', 'asc')
                        ->get();
        // トータルの合計数を取得
        $report_total_shipping_quantity = $items->sum('total_shipping_quantity');
        // 合計出荷数が0の場合
        if($report_total_shipping_quantity === 0){
            throw new \RuntimeException('トータルピッキングリストが作成できません。');
        }
        return compact('items', 'report_total_shipping_quantity');
    }
}