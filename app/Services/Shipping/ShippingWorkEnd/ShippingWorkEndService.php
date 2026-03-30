<?php

namespace App\Services\Shipping\ShippingWorkEnd;

// モデル
use App\Models\ShippingGroup;
use App\Models\Order;
use App\Models\Stock;
use App\Models\ShippingWorkEndHistory;
// 列挙
use App\Enums\OrderStatusEnum;
// その他
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
// 例外
use App\Exceptions\ShippingWorkEndException;

class ShippingWorkEndService
{
    // 出荷完了対象を取得
    public function getShippingWorkEndTarget()
    {
        return Order::getShippingWorkEndTarget()
                    ->lockForUpdate()
                    ->get();
    }

    // 出荷完了対象が正常に完了処理できるか確認
    public function isShippingWorkEndAvailable($orders)
    {
        // 出荷完了対象が存在しない場合
        if($orders->isEmpty()){
            throw new ShippingWorkEndException('出荷完了対象が存在しないため、出荷完了を中断しました。', $orders->count(), 0);
        }
        // 配送伝票番号がNullの受注がある場合
        if($orders->contains('tracking_no', null)){
            throw new ShippingWorkEndException('出荷完了対象に配送伝票番号が未更新の受注が存在するため、出荷完了を中断しました。', $orders->count(), 0);
        }
        // 出荷検品が未完了の受注がある場合
        if($orders->contains(fn($order) => !$order->is_shipping_inspection_complete)){
            throw new ShippingWorkEndException('出荷完了対象に出荷検品が未完了の受注が存在するため、出荷完了を中断しました。', $orders->count(), 0);
        }
        // 受注管理IDを取得
        return $orders->pluck('order_control_id');
    }

    // stocksテーブル更新処理
    public function updateStock($order_control_ids)
    {
        // 出荷完了される受注のorder_item_lotsを取得（在庫管理されている商品のみ）
        $order_item_lots = Order::join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                                ->join('order_item_lots', 'order_item_lots.order_item_id', 'order_items.order_item_id')
                                ->join('items', 'items.item_code', 'order_items.order_item_code')
                                ->join('bases', 'bases.base_id', 'orders.shipping_base_id')
                                ->whereIn('orders.order_control_id', $order_control_ids)
                                ->where('items.is_stock_managed', 1)
                                ->select(
                                    'items.item_id',
                                    'items.item_code',
                                    'bases.base_id as shipping_base_id',
                                    'bases.base_name',
                                    'order_item_lots.lot',
                                    'order_item_lots.exp',
                                    DB::raw('SUM(order_item_lots.quantity) as total_shipping_quantity')
                                )
                                ->groupBy(
                                    'items.item_id',
                                    'items.item_code',
                                    'bases.base_id',
                                    'bases.base_name',
                                    'order_item_lots.lot',
                                    'order_item_lots.exp',
                                )
                                ->lockForUpdate()
                                ->get();
        // 更新に必要な情報を格納する配列を初期化
        $stocks = [];
        // 検品結果の分だけループ処理
        foreach($order_item_lots as $order_item_lot){
            // item_id + base_id + lot + exp で在庫を取得
            $stock = Stock::where('item_id', $order_item_lot->item_id)
                            ->where('base_id', $order_item_lot->shipping_base_id)
                            ->where('lot', $order_item_lot->lot)
                            ->where('exp', $order_item_lot->exp)
                            ->first();
            // 在庫が取得できなかった場合エラー
            if(is_null($stock)){
                throw new ShippingWorkEndException(
                    "在庫が取得できない商品があったため、出荷完了を中断しました。\n".
                    "出荷倉庫：".$order_item_lot->base_name."\n".
                    "商品コード：".$order_item_lot->item_code."\n".
                    "LOT：".$order_item_lot->lot."\n".
                    "EXP：".formatExp($order_item_lot->exp),
                    $order_control_ids->count(), 0
                );
            }
            // 配列に情報を格納
            $stocks[] = [
                'stock_id'  => $stock->stock_id,
                'base_name' => $order_item_lot->base_name,
                'item_code' => $order_item_lot->item_code,
                'lot'       => $order_item_lot->lot,
                'exp'       => $order_item_lot->exp,
                'quantity'  => -(int)$order_item_lot->total_shipping_quantity,
            ];
        }
        // stock_idだけを抜き出し
        $stock_ids = collect($stocks)->pluck('stock_id');
        // 更新対象の在庫レコードを取得する
        $locked_stocks = Stock::whereIn('stock_id', $stock_ids)->lockForUpdate()->get();
        // 更新対象の在庫の分だけループ処理
        foreach($stocks as $stock){
            // 在庫数を減らす対象を取得
            $locked_stock = $locked_stocks->where('stock_id', $stock['stock_id'])->first();
            // 在庫数より出荷数の方が大きければエラーを返す
            if($locked_stock->total_stock < abs($stock['quantity'])){
                throw new ShippingWorkEndException(
                        "在庫数がマイナスになる在庫があるため、出荷完了を中断しました。\n".
                        "出荷倉庫：".$stock['base_name']."\n".
                        "商品コード：".$stock['item_code']."\n".
                        "LOT：".$stock['lot']."\n".
                        "EXP：".formatExp($stock['exp'])."\n".
                        "出荷数：".$stock['quantity'],
                        $order_control_ids->count(), 0
                    );
            }
            // 在庫数から出荷数を引く（マイナス符号がついているので、incrementにしている）
            $locked_stock->increment('total_stock', $stock['quantity']);
        }
        return $stocks;
    }

    // ordersテーブル更新処理
    public function updateOrder($order_control_ids)
    {
        // 出荷日、受注ステータス、出荷グループIDを更新
        Order::whereIn('order_control_id', $order_control_ids)->update([
            'shipping_date'     => CarbonImmutable::now()->toDateString(),
            'order_status_id'   => OrderStatusEnum::SHUKKA_ZUMI,
            'shipping_group_id' => Null,
        ]);
    }
    
    // 出荷グループを削除
    public function deleteShippingGroup()
    {
        // 受注が紐付いていない出荷グループがあれば削除
        ShippingGroup::doesntHave('orders')->delete();
    }

    // 出荷完了履歴に追加
    public function createShippingWorkEndHistory($target_count, $is_successful, $message)
    {
        // レコードを追加
        ShippingWorkEndHistory::create([
            'target_count'  => $target_count,
            'is_successful' => $is_successful,
            'message'       => $message,
        ]);
    }
}