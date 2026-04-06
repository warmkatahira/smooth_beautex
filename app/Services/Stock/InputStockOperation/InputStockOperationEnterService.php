<?php

namespace App\Services\Stock\InputStockOperation;

// モデル
use App\Models\Stock;
use App\Models\OrderItem;
// 列挙
use App\Enums\OrderStatusEnum;

class InputStockOperationEnterService
{
    // 在庫操作するデータを取得
    public function getOperationData($quantity)
    {
        // 値がnull又は0の要素を取り除く
        $quantity = array_filter($quantity);
        // 在庫操作する内容を格納する配列を初期化
        $stock_update_arr = [];
        // stock_idの分だけループ処理
        foreach($quantity as $stock_id => $qty){
            $stock_update_arr[] = [
                'stock_id' => $stock_id,
                'quantity' => $qty,
            ];
        }
        return $stock_update_arr;
    }

    // 操作対象の在庫をロック
    public function lockStock($stock_update_arr)
    {
        // stock_idを取得
        $stock_ids = array_column($stock_update_arr, 'stock_id');
        // 操作する在庫をロック
        Stock::whereIn('stock_id', $stock_ids)
                    ->lockForUpdate()
                    ->get();
    }

    // 在庫操作できる内容か確認
    public function check($stock_update_arr)
    {
        // 在庫操作対象の分だけループ処理
        foreach($stock_update_arr as $stock_update){
            // 在庫を取得
            $stock = Stock::getSpecify($stock_update['stock_id'])->with(['base', 'item'])->first();
            // 数量がマイナスの場合
            if($stock_update['quantity'] < 0){
                // 商品×倉庫単位の合計在庫数を取得
                $total_stock = Stock::where('base_id', $stock->base_id)
                                    ->where('item_id', $stock->item_id)
                                    ->sum('total_stock');
                // 在庫数がマイナスになる数量ではないか(絶対値で比較している)
                if($total_stock < abs($stock_update['quantity'])){
                    throw new \RuntimeException("在庫数がマイナスになる数量が入力されている箇所があります。\n".
                                                "倉庫名:".$stock->base->base_name."\n".
                                                "商品コード：".$stock->item->item_code."\n".
                                                "商品名：".$stock->item->item_name
                                            );
                }
                // 出荷前の受注で引き当たっている数量を取得
                $already_allocated = OrderItem::join('orders', 'orders.order_control_id', 'order_items.order_control_id')
                                        ->join('items', 'items.item_code', '=', 'order_items.order_item_code')
                                        ->where('orders.shipping_base_id', $stock->base_id)
                                        ->where('items.item_id', $stock->item_id)
                                        ->where('orders.order_status_id', '<', OrderStatusEnum::SHUKKA_ZUMI)
                                        ->where('orders.is_stock_allocation_skipped', 0)
                                        ->whereRaw('order_items.shipping_quantity - order_items.unallocated_quantity > 0')
                                        ->selectRaw('SUM(order_items.shipping_quantity - order_items.unallocated_quantity) as allocated_quantity')
                                        ->value('allocated_quantity') ?? 0;
                // 操作後の在庫数を計算
                $after_total_stock = $total_stock + $stock_update['quantity'];
                // 操作後の在庫数が引当済み数量を下回る場合
                if($after_total_stock < $already_allocated){
                    throw new \RuntimeException("引当済み数量を下回る数量が入力されている箇所があります。\n".
                                                "倉庫名:".$stock->base->base_name."\n".
                                                "商品コード：".$stock->item->item_code."\n".
                                                "商品名：".$stock->item->item_name."\n".
                                                "現在の在庫数：".$total_stock."\n".
                                                "操作数量：".$stock_update['quantity']."\n".
                                                "引当済み数量：".$already_allocated."\n".
                                                "操作後の在庫数：".$after_total_stock
                                            );
                }
            }
        }
    }
}