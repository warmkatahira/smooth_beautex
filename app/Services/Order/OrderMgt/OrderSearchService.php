<?php

namespace App\Services\Order\OrderMgt;

// モデル
use App\Models\Order;
// サービス
use App\Services\Common\BaseFilterService;
// 列挙
use App\Enums\SystemEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\RouteNameEnum;
// その他
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class OrderSearchService extends BaseFilterService
{
    // セッションを削除
    public function deleteSession()
    {
        // 全セッションキーを取得して、「filter_」で始まるかつ、filter_shipping_group_id以外のキーを取得
        $keys = collect(session()->all())
                    ->keys()
                    ->filter(fn($key) => str_starts_with($key, 'filter_') && $key !== 'filter_shipping_group_id');
        // 取得したセッションを削除
        session()->forget($keys->all());
        // 出荷管理以外のページの場合
        if(Route::currentRouteName() !== RouteNameEnum::SHIPPING_MGT){
            // セッションを削除
            session()->forget('filter_shipping_group_id');
        }
    }

    // セッションに検索条件を格納
    public function setSearchCondition($request)
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // パラメータがあればパラメータを活かし、なければ指定した値をセット
        session(['filter_order_status_id' => isset($request->filter_order_status_id) ? $request->filter_order_status_id : OrderStatusEnum::KAKUNIN_MACHI ]);
        // 変数が存在しない場合
        if(!isset($request->filter_type)){
            // 検索が実行されていないので、初期条件をセット
        }
        // 「filter」の場合
        if($request->process_type === 'filter'){
            // 「filter_」から始まるかつ、filter_order_status_id以外のキーパラメータをセッションに格納
            collect($request->all())
                    ->keys()
                    ->filter(fn($key) => str_starts_with($key, 'filter_') && $key !== 'filter_order_status_id')
                    ->each(fn($key) => session([$key => $request->$key]));
        }
    }

    // ベースクエリ
    protected function baseQuery()
    {
        return Order::where('order_status_id', session('filter_order_status_id'))
                    ->with(['order_items.item', 'order_items.order_item_lots', 'order_category.mall', 'shipping_method.delivery_company'])
                    ->leftJoin('order_items', 'order_items.order_control_id', '=', 'orders.order_control_id')
                    ->groupBy('orders.order_control_id')
                    ->select([
                        'orders.*',
                        DB::raw('COUNT(DISTINCT order_items.package_no) as package_count'),
                        // 出荷検品完了かつ、stocksに存在しないLOT×EXPがスキャンされている件数
                        DB::raw('(
                            SELECT COUNT(*) FROM order_item_lots oil
                            INNER JOIN order_items oi ON oi.order_item_id = oil.order_item_id
                            INNER JOIN items i ON i.item_code = oi.order_item_code
                            WHERE oi.order_control_id = orders.order_control_id
                            AND orders.is_shipping_inspection_complete = true
                            AND oil.lot IS NOT NULL
                            AND NOT EXISTS (
                                SELECT 1 FROM stocks s
                                WHERE s.lot     = oil.lot
                                    AND s.exp     = oil.exp
                                    AND s.item_id = i.item_id
                                    AND s.base_id = orders.shipping_base_id
                            )
                        ) as invalid_lot_count'),
                    ]);
    }

    // LIKEキー
    protected function likeKeys(): array
    {
        return [
            'filter_order_import_time',
            'filter_order_no',
            'filter_order_time',
            'filter_order_control_id',
            'filter_order_mark',
            'filter_ship_name',
            'filter_ship_region_type',
            'filter_desired_delivery_time',
            'filter_tracking_no',
        ];
    }

    // 特殊キー
    protected function specialKeys(): array
    {
        return [
            // 取込日
            'filter_order_import_date_from' => function ($query, $value) {
                $query->whereDate('order_import_date', '>=', session('filter_order_import_date_from'))
                    ->whereDate('order_import_date', '<=', session('filter_order_import_date_to'));
            },
            // 注文日
            'filter_order_date_from' => function ($query, $value) {
                $query->whereDate('order_date', '>=', session('filter_order_date_from'))
                    ->whereDate('order_date', '<=', session('filter_order_date_to'));
            },
            // モール
            'filter_mall_id' => function ($query, $value) {
                $query->whereHas('order_category.mall', function ($q) use ($value) {
                    $q->where('mall_id', $value);
                });
            },
            // 配送地域
            'filter_ship_region_type' => function ($query, $value) {
                if($value === '国内'){
                    $query->where('ship_country_code', 'JP');
                }else{
                    $query->where('ship_country_code', '!=', 'JP');
                }
            },
            // 運送会社
            'filter_shipping_delivery_company_id' => function ($query, $value) {
                $query->whereHas('shipping_method.delivery_company', function ($q) use ($value) {
                    $q->where('delivery_company_id', $value);
                });
            },
            // 出荷日
            'filter_shipping_date_from' => function ($query, $value) {
                $query->whereDate('shipping_date', '>=', session('filter_shipping_date_from'))
                    ->whereDate('shipping_date', '<=', session('filter_shipping_date_to'));
            },
            // 配送希望日
            'filter_desired_delivery_date_from' => function ($query, $value) {
                $query->whereDate('desired_delivery_date', '>=', session('filter_desired_delivery_date_from'))
                    ->whereDate('desired_delivery_date', '<=', session('filter_desired_delivery_date_to'));
            },
            // 出荷個口No
            'filter_package_count' => function ($query, $value) {
                $query->having(DB::raw('COUNT(DISTINCT order_items.package_no)'), '=', $value);
            },
            // 出荷検品LOT
            'filter_shipping_inspection_lot' => function ($query, $value) {
                $query->where('orders.is_shipping_inspection_complete', true)
                    ->having(DB::raw('(
                        SELECT COUNT(*) FROM order_item_lots oil
                        INNER JOIN order_items oi ON oi.order_item_id = oil.order_item_id
                        INNER JOIN items i ON i.item_code = oi.order_item_code
                        WHERE oi.order_control_id = orders.order_control_id
                            AND oil.lot IS NOT NULL
                            AND NOT EXISTS (
                                SELECT 1 FROM stocks s
                                WHERE s.lot     = oil.lot
                                AND s.exp     = oil.exp
                                AND s.item_id = i.item_id
                                AND s.base_id = orders.shipping_base_id
                            )
                    )'), $value === '1' ? '>' : '=', 0);
            },
        ];
    }

    // 無視するキー
    protected function ignoreKeys(): array
    {
        return [
            'filter_order_import_date_to',
            'filter_order_date_to',
            'filter_shipping_date_to',
            'filter_desired_delivery_date_to',
        ];
    }

    // 並び替え
    protected function applySort($query)
    {
        return $query->orderBy('order_import_date', 'asc')
                    ->orderBy('order_import_time', 'asc')
                    ->orderBy('order_control_id', 'asc');
    }
}