<?php

namespace App\Services\Order\OrderAllocate;

// モデル
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
use App\Models\Stock;
// サービス
use App\Services\Common\MieruService;
// 列挙
use App\Enums\OrderStatusEnum;
// その他
use Illuminate\Support\Facades\DB;

class OrderAllocateService
{
    // 引当処理
    public function procOrderAllocate($order_control_id)
    {
        try {
            DB::transaction(function () use ($order_control_id){
                // 引当対象を取得
                $allocate_orders = $this->getAllocateOrder($order_control_id);
                // 引当対象がない場合
                if($allocate_orders->isEmpty()){
                    // 処理終了
                    return;
                }
                // 商品引当処理
                $this->procItemAllocate($allocate_orders);
                // 引当対象在庫のロック（引当対象がいるかの結果も返す）
                $result = $this->lockStockForAllocation($allocate_orders);
                // 引当対象がいる場合
                if($result){
                    // 在庫引当処理
                    $this->procStockAllocate($allocate_orders);
                    // 引当済み処理
                    $this->procAllocated($allocate_orders);
                }
            });
        } catch (\Exception $e){
            return with([
                'type' => 'error',
                'message' => '引当処理に失敗しました。',
            ]);
        }
        // インスタンス化
        $MieruService = new MieruService;
        // ミエルの進捗を更新する対象を取得
        $MieruService->getUpdateProgressTarget(null);
        return with([
            'type' => 'success',
            'message' => '引当処理が完了しました。',
        ]);
    }

    // 引当対象を取得
    public function getAllocateOrder($order_control_id)
    {
        // 受注管理IDのパラメータがNullの場合(指定がない場合)
        if(is_null($order_control_id)){
            // 引当済みが「0」かつ出荷倉庫IDがNull以外の受注を取得（ここでロック）
            $allocate_orders = Order::where('is_allocated', 0)
                                ->whereNotNull('shipping_base_id')
                                ->select('order_control_id')
                                ->lockForUpdate()
                                ->get();
        }
        // 受注管理IDのパラメータがNull以外の場合(指定がある場合)
        if(!is_null($order_control_id)){
            // 引当済みが「0」かつ出荷倉庫IDがNull以外の受注を取得（ここでロック）
            $allocate_orders = Order::where('order_control_id', $order_control_id)
                                ->where('is_allocated', 0)
                                ->whereNotNull('shipping_base_id')
                                ->select('order_control_id')
                                ->lockForUpdate()
                                ->get();
        }
        // 受注管理IDを取得
        $order_control_ids = $allocate_orders->pluck('order_control_id');
        // ordersに関連するorder_itemsをロック
        $order_items = OrderItem::whereIn('order_control_id', $order_control_ids)
                        ->lockForUpdate()
                        ->get();
        return $allocate_orders;
    }

    // 商品引当処理
    public function procItemAllocate($allocate_orders)
    {
        // itemsテーブルで商品引当がOKになる対象を取得
        $order_items = OrderItem::whereIn('order_control_id', $allocate_orders)
                            ->where('is_item_allocated', 0)
                            ->join('items', 'items.item_code', 'order_items.order_item_code')
                            ->pluck('order_item_id');
        // 商品引当をOK「1」に更新
        OrderItem::whereIn('order_item_id', $order_items)
                    ->update([
                        'is_item_allocated' => 1,
                    ]);
        // 商品引当NG対象を取得
        $item_allocate_ng_orders = OrderItem::whereIn('order_control_id', $allocate_orders)
                                        ->where('is_item_allocated', 0)
                                        ->select('order_control_id')
                                        ->distinct()
                                        ->pluck('order_control_id');
        // 商品引当がNGの受注はここで注文ステータスを「確認待ち」に更新する
        Order::whereIn('order_control_id', $item_allocate_ng_orders)->update([
            'order_status_id' => OrderStatusEnum::KAKUNIN_MACHI,
        ]);
    }

    // 引当対象在庫のロック（引当対象がいるかの結果も返す）
    public function lockStockForAllocation($allocate_orders)
    {
        // 在庫引当対象の商品IDと出荷倉庫IDを重複を除いて取得
        $stock_allocate_items = Order::join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                                    ->join('items', 'items.item_code', 'order_items.order_item_code')
                                    ->whereIn('orders.order_control_id', $allocate_orders)
                                    ->where('order_items.is_stock_allocated', 0)
                                    ->select('shipping_base_id', 'item_id')
                                    ->groupBy('shipping_base_id', 'item_id')
                                    ->get();
        // レコードが取得できていない場合
        if($stock_allocate_items->isEmpty()){
            // 処理を抜ける
            return false;
        }
        // 一時テーブルを作成
        DB::statement('
            CREATE TEMPORARY TABLE temp_stock_allocate_items (
                base_id VARCHAR(10) COLLATE utf8mb4_unicode_ci,
                item_id INT
            );
        ');
        // 一時テーブルに追加するデータを取得
        $values = $stock_allocate_items->map(function ($item){
                        return "('" . $item->shipping_base_id . "'," . (int) $item->item_id . ")";
                    })->implode(',');
        // 一時テーブルに追加
        DB::statement("
            INSERT INTO temp_stock_allocate_items (base_id, item_id)
            VALUES $values
        ");
        // 在庫テーブルと一時テーブルを結合して、引当対象の在庫をロック
        Stock::join('temp_stock_allocate_items', function ($join){
                    $join->on('stocks.base_id', '=', 'temp_stock_allocate_items.base_id')
                        ->on('stocks.item_id', '=', 'temp_stock_allocate_items.item_id');
                })
                ->select('stocks.*')
                ->lockForUpdate()
                ->get();
        return true;
    }

    // 在庫引当処理
    public function procStockAllocate($allocate_orders)
    {
        // 在庫引当の条件を満たしていて在庫管理していない商品のレコードを取得
        $order_item_ids = Order::join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                                    ->join('items', 'items.item_code', 'order_items.order_item_code')
                                    ->whereIn('orders.order_control_id', $allocate_orders)
                                    ->where('order_items.is_stock_allocated', 0)
                                    ->where('items.is_stock_managed', 0)
                                    ->pluck('order_items.order_item_id');
        // 在庫引当をOKに更新(在庫管理をしていない商品は無条件で在庫引当OKにしている)
        OrderItem::whereIn('order_item_id', $order_item_ids)->update([
            'is_stock_allocated'    => 1,
            'unallocated_quantity'  => 0,
        ]);
        // 在庫引当の条件を満たしていて在庫管理している商品のレコードを取得(注文番号で昇順をかけている)
        $stock_allocate_orders = Order::join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                                    ->join('items', 'items.item_code', '=', 'order_items.order_item_code')
                                    ->whereIn('orders.order_control_id', $allocate_orders)
                                    ->where('order_items.is_stock_allocated', 0)
                                    ->where('items.is_stock_managed', 1)
                                    ->select('order_items.order_item_id', 'orders.shipping_base_id', 'items.item_id', 'order_items.unallocated_quantity')
                                    ->orderBy('orders.order_no', 'asc')
                                    ->get();
        // 引当対象の分だけループ
        foreach($stock_allocate_orders as $stock_allocate_order){
            // 引当対象のレコードを取得
            $order_item = OrderItem::getSpecify($stock_allocate_order->order_item_id)->first();
            // item×baseの合計在庫数を取得
            $total_stock = Stock::where('base_id', $stock_allocate_order->shipping_base_id)
                                ->where('item_id', $stock_allocate_order->item_id)
                                ->sum('total_stock');
            // 注文ステータスが出荷前で引き当てされている数量を取得
            $already_allocated = OrderItem::join('orders', 'orders.order_control_id', 'order_items.order_control_id')
                                        ->join('items', 'items.item_code', '=', 'order_items.order_item_code')
                                        ->where('orders.shipping_base_id', $stock_allocate_order->shipping_base_id)
                                        ->where('items.item_id', $stock_allocate_order->item_id)
                                        ->where('orders.order_status_id', '<', OrderStatusEnum::SHUKKA_ZUMI)
                                        ->whereNotIn('orders.order_control_id', $allocate_orders)
                                        ->whereRaw('order_items.shipping_quantity - order_items.unallocated_quantity > 0')
                                        ->selectRaw('SUM(order_items.shipping_quantity - order_items.unallocated_quantity) as allocated_quantity')
                                        ->value('allocated_quantity') ?? 0;
            // 引当可能残数を計算
            $available_quantity = $total_stock - $already_allocated;
            // 引当可能残数が今回引き当てたい数量以上の場合
            if($available_quantity >= $order_item->unallocated_quantity){
                // 全数引当OK
                $order_item->update([
                    'is_stock_allocated'   => 1,
                    'unallocated_quantity' => 0,
                ]);
            }elseif($available_quantity > 0){
                // 一部だけ引当OKなので、引当残を引当可能残数だけ減らす
                $order_item->decrement('unallocated_quantity', $available_quantity);
            }
        }
    }

    // 引当済み処理
    public function procAllocated($allocate_orders)
    {
        // 対象を取得
        $orders = Order::whereIn('order_control_id', $allocate_orders)->with('order_items')->get();
        // 対象の分だけループ処理
        foreach($orders as $order){
            // 配下レコードの商品引当NG数を取得
            $item_allocated_ng_count = $order->order_items->where('is_item_allocated', 0)->count();
            // 配下レコードの在庫引当NG数を取得
            $stock_allocated_ng_count = $order->order_items->where('is_stock_allocated', 0)->count();
            // 優先順位: 商品引当NG > 在庫引当NG > 引当OK
            if($item_allocated_ng_count >= 1){
                $is_allocated = 0;
                $order_status_id = OrderStatusEnum::KAKUNIN_MACHI;
            }elseif($stock_allocated_ng_count >= 1){
                $is_allocated = 0;
                $order_status_id = OrderStatusEnum::HIKIATE_MACHI;
            }else{
                $is_allocated = 1;
                $order_status_id = OrderStatusEnum::SHUKKA_MACHI;
            }
            // 変数の値に沿って更新
            $order->update([
                'is_allocated'      => $is_allocated,
                'order_status_id'   => $order_status_id,
            ]);
        }
    }
}