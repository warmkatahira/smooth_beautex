<?php

namespace App\Services\Stock\Stock;

// モデル
use App\Models\Base;
use App\Models\Order;
// 列挙
use App\Enums\OrderStatusEnum;
use App\Enums\RouteNameEnum;
// その他
use Illuminate\Support\Facades\DB;

class StockSearchService
{
    // 在庫検索用の初期セッションを設定
    public function setSearchCondition($request)
    {
        // filterリクエストの場合はリクエストの値を優先（クリアボタンで空にできるように）
        if($request->process_type === 'filter'){
            session(['filter_total_stock_min' => $request->filter_total_stock_min]);
        // 初回アクセスの場合のみデフォルト値をセット
        } elseif(!session()->has('filter_total_stock_min')){
            session(['filter_total_stock_min' => '1']);
        }
    }

    // 検索結果を取得して集計
    public function getSearchResultAndAggregateData($query, $route_name)
    {
        // 倉庫を取得
        $bases = Base::getAll()->get();
        // $queryで取得しているitemsの結果と全ての倉庫の組み合わせを取得
        $query = $query->crossJoin('bases')
                    ->select(
                        'items.item_id',
                        'items.item_code',
                        'items.item_jan_code',
                        'items.color_id',
                        'items.color_row',
                        'items.brand',
                        'items.wearing_period',
                        'items.quantity_per_box',
                        'items.manufacturer',
                        'items.supplier',
                        'items.item_name',
                        'items.item_category_1',
                        'items.item_category_2',
                        'items.item_image_file_name',
                        'items.is_stock_managed',
                        'items.sort_order as item_sort_order',
                        'bases.base_id',
                        'bases.base_name',
                        'bases.base_color_code',
                        'bases.sort_order as base_sort_order',
                    );
        // 倉庫の条件がある場合
        if(session('filter_base_id') != null){
            // 条件を指定して取得
            $query = $query->where('base_id', session('filter_base_id'));
        }
        // クエリをサブクエリ化して「item_base」という別名をつける
        $query = DB::query()->fromSub($query, 'item_base');
        if($route_name === RouteNameEnum::STOCK_BY_ITEM){
            // item × base 単位で集計してJOIN（重複防止）
            $stocks_sub_query = DB::table('stocks')
                ->select(
                    'item_id',
                    'base_id',
                    DB::raw('SUM(total_stock) as total_stock'),
                )
                ->groupBy('item_id', 'base_id');
            $query = $query->leftJoinSub($stocks_sub_query, 'stocks', function($join){
                $join->on('stocks.item_id', '=', 'item_base.item_id')
                    ->on('stocks.base_id', '=', 'item_base.base_id');
            });
        } else {
            // LOT・EXP別にそのままJOIN
            $query = $query->join('stocks', function($join){
                $join->on('stocks.item_id', '=', 'item_base.item_id')
                    ->on('stocks.base_id', '=', 'item_base.base_id');
            });
        }
        // 在庫数（以上）の条件がある場合
        if(session('filter_total_stock_min') !== null){
            $query = $query->where('stocks.total_stock', '>=', session('filter_total_stock_min'));
        }
        // 在庫数（以下）の条件がある場合
        if(session('filter_total_stock_max') !== null){
            $query = $query->where('stocks.total_stock', '<=', session('filter_total_stock_max'));
        }
        // LOTの条件がある場合
        if(session('filter_lot') != null){
            // 条件を指定して取得
            $query = $query->where('stocks.lot', 'LIKE', '%'.session('filter_lot').'%');
        }
        // EXPの条件がある場合
        if(session('filter_exp') != null){
            // 条件を指定して取得
            $query = $query->where('stocks.exp', 'LIKE', '%'.session('filter_exp').'%');
        }
        // STOCK_BY_ITEMの場合のみJOIN
        if($route_name === RouteNameEnum::STOCK_BY_ITEM){
            // 受注数を商品×出荷倉庫毎で取得
            $shipping_quantity_sub_query = Order::join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                                            ->join('items', 'items.item_id', 'order_items.item_id')
                                            ->where('order_status_id', '<', OrderStatusEnum::SHUKKA_ZUMI)
                                            ->select(
                                                'items.item_id',
                                                'orders.shipping_base_id',
                                                DB::raw('SUM(order_items.shipping_quantity) as total_shipping_quantity')
                                            )
                                            ->groupBy('items.item_id', 'orders.shipping_base_id');
            // queryとshipping_quantity_sub_queryを結合
            $query = $query->leftJoinSub($shipping_quantity_sub_query, 'shipping_quantity_sub_query', function($join){
                $join->on('shipping_quantity_sub_query.item_id', '=', 'item_base.item_id')
                    ->on('shipping_quantity_sub_query.shipping_base_id', '=', 'item_base.base_id');
            });
        }
        // 商品単位表示の場合
        if($route_name === RouteNameEnum::STOCK_BY_ITEM){
            // 結果にカラムを追加
            $query->addSelect(
                'item_base.item_id',
                'item_base.item_code',
                'item_base.item_jan_code',
                'item_base.color_id',
                'item_base.color_row',
                'item_base.brand',
                'item_base.wearing_period',
                'item_base.quantity_per_box',
                'item_base.manufacturer',
                'item_base.supplier',
                'item_base.item_name',
                'item_base.item_category_1',
                'item_base.item_category_2',
                'item_base.item_image_file_name',
                'item_base.is_stock_managed',
                DB::raw("CASE item_base.is_stock_managed WHEN 0 THEN '無効' WHEN 1 THEN '有効' END as is_stock_managed_text"),
            );
            // 倉庫ごとの在庫・受注数のカラムを追加
            foreach ($bases as $base){
                $query->addSelect(DB::raw("
                    SUM(CASE WHEN item_base.base_id = '{$base->base_id}' THEN stocks.total_stock ELSE 0 END) as total_stock_{$base->base_id},
                    SUM(CASE WHEN item_base.base_id = '{$base->base_id}' THEN shipping_quantity_sub_query.total_shipping_quantity ELSE 0 END) as total_shipping_quantity_{$base->base_id}
                "));
            }
            // グループ化
            $query = $query->groupBy(
                'item_base.item_id',
                'item_base.item_code',
                'item_base.item_jan_code',
                'item_base.color_id',
                'item_base.color_row',
                'item_base.brand',
                'item_base.wearing_period',
                'item_base.quantity_per_box',
                'item_base.manufacturer',
                'item_base.supplier',
                'item_base.item_name',
                'item_base.item_category_1',
                'item_base.item_category_2',
                'item_base.item_image_file_name',
            )->orderBy('item_base.item_sort_order', 'asc')
            ->orderBy('item_base.item_code', 'asc');
        }
        // 在庫単位表示の場合
        if($route_name === RouteNameEnum::STOCK_BY_STOCK || $route_name === RouteNameEnum::INPUT_STOCK_OPERATION){
            // 結果にカラムを追加
            $query->addSelect(
                'item_base.item_id',
                'item_base.item_code',
                'item_base.item_jan_code',
                'item_base.color_id',
                'item_base.color_row',
                'item_base.brand',
                'item_base.wearing_period',
                'item_base.quantity_per_box',
                'item_base.manufacturer',
                'item_base.supplier',
                'item_base.item_name',
                'item_base.item_category_1',
                'item_base.item_category_2',
                'item_base.item_image_file_name',
                'item_base.is_stock_managed',
                DB::raw("CASE item_base.is_stock_managed WHEN 0 THEN '無効' WHEN 1 THEN '有効' END as is_stock_managed_text"),
                'item_base.base_id',
                'item_base.base_name',
                'item_base.base_color_code',
                DB::raw('IFNULL(stocks.total_stock, 0) as total_stock'),
                'stocks.item_location',
                'stocks.lot',
                'stocks.exp',
                'stocks.stock_id',
            );
            // グループ化
            $query = $query->orderBy('item_base.base_sort_order', 'asc')
                        ->orderBy('item_base.item_sort_order', 'asc')
                        ->orderBy('stocks.exp', 'asc')
                        ->orderBy('stocks.lot', 'asc');
        }
        
        return with([
            'stocks' => $query,
            'bases' => $bases,
        ]);
    }
}