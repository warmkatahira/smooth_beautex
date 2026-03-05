<?php

namespace App\Http\Controllers\Shipping\ShippingMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\DeliveryCompany;
use App\Models\ShippingGroup;
use App\Models\Base;
use App\Models\OrderCategory;
use App\Models\Prefecture;
// サービス
use App\Services\Shipping\ShippingMgt\ShippingMgtService;
use App\Services\Order\OrderMgt\OrderSearchService;
// 列挙
use App\Enums\OrderStatusEnum;

class ShippingMgtController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '出荷管理']);
        // インスタンス化
        $ShippingMgtService = new ShippingMgtService;
        $OrderSearchService = new OrderSearchService;
        // 注文ステータスのパラメータを追加
        $request->merge([
            'order_status_id' => OrderStatusEnum::SAGYO_CHU,
        ]);
        // 出荷グループを取得
        $shipping_group = $ShippingMgtService->getShippingGroup($request->search_shipping_group_id);
        // 出荷グループに存在する配送方法を取得
        $shipping_methods = $ShippingMgtService->getShippingMethod();
        // セッションを削除
        $OrderSearchService->deleteSession();
        // セッションに検索条件を格納
        $OrderSearchService->setSearchCondition($request);
        // 検索結果を取得
        $result = $OrderSearchService->getSearchResult();
        // ページネーションを実施
        $orders = $OrderSearchService->setPagination($result);
        // 出荷グループを取得
        $shipping_groups = ShippingGroup::getAll()->get();
        // 倉庫を取得
        $bases = Base::getAll()->get();
        // 受注区分を取得
        $order_categories = OrderCategory::getAll()->get();
        // 運送会社を取得
        $delivery_companies = DeliveryCompany::getAll()->with('shipping_methods')->get();
        // 都道府県を取得
        $prefectures = Prefecture::getAll()->get();
        return view('shipping.shipping_mgt.index')->with([
            'orders' => $orders,
            'shipping_groups' => $shipping_groups,
            'shipping_methods' => $shipping_methods,
            'shipping_group' => $shipping_group,
            'bases' => $bases,
            'order_categories' => $order_categories,
            'delivery_companies' => $delivery_companies,
            'prefectures' => $prefectures,
        ]);
    }
}