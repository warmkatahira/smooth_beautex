<?php

namespace App\Services\Stock\Stock;

// モデル
use App\Models\Base;
use App\Models\Order;
use App\Models\Stock;
// 列挙
use App\Enums\SystemEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\RouteNameEnum;
// その他
use Illuminate\Support\Facades\DB;

class StockSearchService
{
    // セッションを削除
    public function deleteSession()
    {
        session()->forget([
            'search_base_id',
            'search_available_stock_status',
            'search_is_stock_managed',
        ]);
    }

    // セッションに検索条件を格納
    public function setSearchCondition($request)
    {
        // 変数が存在しない場合は検索が実行されていないので、初期条件をセット
        if(!isset($request->search_type)){
        }
        // 「search」なら検索が実行されているので、検索条件をセット
        if($request->search_type === 'search'){
            session(['search_base_id' => $request->search_base_id]);
            session(['search_available_stock_status' => $request->search_available_stock_status]);
            session(['search_is_stock_managed' => $request->search_is_stock_managed]);
        }
    }

    // 検索結果を取得
    public function getSearchResult($query, $route_name)
    {
        // 倉庫を取得
        $bases = Base::getAll()->get();
        // $queryで取得しているitemsの結果と全ての倉庫の組み合わせを取得
        $query = $query->crossJoin('bases')
                    ->select(
                        'items.item_id',
                        'items.item_code',
                        'items.item_jan_code',
                        'items.item_name',
                        'items.item_category',
                        'items.item_image_file_name',
                        'items.is_stock_managed',
                        'items.sort_order as item_sort_order',
                        'bases.base_id',
                        'bases.base_name',
                        'bases.base_color_code',
                        'bases.sort_order as base_sort_order',
                    );
        // 倉庫の条件がある場合
        if(session('search_base_id') != null){
            // 条件を指定して取得
            $query = $query->where('base_id', session('search_base_id'));
        }
        // 在庫管理の条件がある場合
        if(session('search_is_stock_managed') != null){
            // 条件を指定して取得
            $query = $query->where('is_stock_managed', session('search_is_stock_managed'));
        }
        // クエリをサブクエリ化して「item_base」という別名をつける
        $query = DB::query()->fromSub($query, 'item_base');
        // queryとstocksを結合
        $query = $query->leftJoin('stocks', function($join){
            $join->on('stocks.item_id', '=', 'item_base.item_id')
                ->on('stocks.base_id', '=', 'item_base.base_id');
        });
        // 有効在庫数状態の条件がある場合
        if(session('search_available_stock_status') != null){
            // 条件を指定して取得
            // 在庫なしの場合
            if(session('search_available_stock_status') == 0){
                $query = $query->where(function ($q) {
                    $q->where('available_stock', 0)
                    ->orWhereNull('available_stock');
                });
            }
            // 在庫ありの場合
            if(session('search_available_stock_status') == 1){
                $query = $query->where('available_stock', '>', 0);
            }
        }
        // 受注数を商品×出荷倉庫毎で取得
        $order_quantity_sub_query = Order::join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                                        ->join('items', 'items.item_code', 'order_items.order_item_code')
                                        ->where('order_status_id', '<', OrderStatusEnum::SHUKKA_ZUMI)
                                        ->select(
                                            'items.item_id',
                                            'orders.shipping_base_id',
                                            DB::raw('SUM(order_items.order_quantity) as total_order_quantity')
                                        )
                                        ->groupBy('items.item_id', 'orders.shipping_base_id');
        // queryとorder_quantity_sub_queryを結合
        $query = $query->leftJoinSub($order_quantity_sub_query, 'order_quantity_sub_query', function($join){
            $join->on('order_quantity_sub_query.item_id', '=', 'item_base.item_id')
                ->on('order_quantity_sub_query.shipping_base_id', '=', 'item_base.base_id');
        });
        // 商品単位表示の場合
        if($route_name === RouteNameEnum::STOCK_BY_ITEM){
            // 結果にカラムを追加
            $query->addSelect(
                'item_base.item_id',
                'item_base.item_code',
                'item_base.item_jan_code',
                'item_base.item_name',
                'item_base.item_category',
                'item_base.item_image_file_name',
                'item_base.is_stock_managed',
                DB::raw("CASE item_base.is_stock_managed WHEN 0 THEN '無効' WHEN 1 THEN '有効' END as is_stock_managed_text"),
            );
            // 倉庫ごとの在庫・受注数のカラムを追加
            foreach ($bases as $base){
                $query->addSelect(DB::raw("
                    SUM(CASE WHEN item_base.base_id = '{$base->base_id}' THEN stocks.total_stock ELSE 0 END) as total_stock_{$base->base_id},
                    SUM(CASE WHEN item_base.base_id = '{$base->base_id}' THEN stocks.available_stock ELSE 0 END) as available_stock_{$base->base_id},
                    SUM(CASE WHEN item_base.base_id = '{$base->base_id}' THEN order_quantity_sub_query.total_order_quantity ELSE 0 END) as total_order_quantity_{$base->base_id}
                "));
            }
            // グループ化
            $query = $query->groupBy(
                'item_base.item_id',
                'item_base.item_code',
                'item_base.item_jan_code',
                'item_base.item_name',
                'item_base.item_category',
                'item_base.item_image_file_name',
            )->orderBy('item_base.item_sort_order', 'asc');
        }
        // 在庫単位表示の場合
        if($route_name === RouteNameEnum::STOCK_BY_STOCK || $route_name === RouteNameEnum::INPUT_STOCK_OPERATION){
            // 結果にカラムを追加
            $query->addSelect(
                'item_base.item_id',
                'item_base.item_code',
                'item_base.item_jan_code',
                'item_base.item_name',
                'item_base.item_category',
                'item_base.item_image_file_name',
                'item_base.is_stock_managed',
                DB::raw("CASE item_base.is_stock_managed WHEN 0 THEN '無効' WHEN 1 THEN '有効' END as is_stock_managed_text"),
                'item_base.base_id',
                'item_base.base_name',
                'item_base.base_color_code',
                DB::raw('IFNULL(stocks.total_stock, 0) as total_stock'),
                DB::raw('IFNULL(order_quantity_sub_query.total_order_quantity, 0) as total_order_quantity'),
                DB::raw('IFNULL(stocks.available_stock, 0) as available_stock'),
                'stocks.item_location',
            );
            // グループ化
            $query = $query->orderBy('item_base.base_sort_order', 'asc')
                        ->orderBy('item_base.item_sort_order', 'asc');
        }
        return with([
            'stocks' => $query,
            'bases' => $bases,
        ]);
    }
}