<?php

namespace App\Http\Controllers\Shipping\ShippingHistory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\DeliveryCompany;
use App\Models\Base;
use App\Models\OrderCategory;
use App\Models\Prefecture;
use App\Models\Mall;
// サービス
use App\Services\Order\OrderMgt\OrderSearchService;
use App\Services\Shipping\ShippingHistory\ShippingHistoryService;
// リクエスト
use App\Http\Requests\Order\OrderMgt\OrderSearchRequest;
// 列挙
use App\Enums\OrderStatusEnum;
use App\Enums\ShipRegionTypeEnum;
// トレイト
use App\Traits\PaginatesResultsTrait;

class ShippingHistoryController extends Controller
{
    use PaginatesResultsTrait;
    
    public function index(OrderSearchRequest $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '出荷履歴']);
        // インスタンス化
        $OrderSearchService = new OrderSearchService;
        $ShippingHistoryService = new ShippingHistoryService;
        // 注文ステータスのパラメータを追加
        $request->merge([
            'filter_order_status_id' => OrderStatusEnum::SHUKKA_ZUMI,
        ]);
        // セッションを削除
        $OrderSearchService->deleteSession();
        // セッションに検索条件を格納
        $OrderSearchService->setSearchCondition($request);
        $ShippingHistoryService->setSearchCondition($request);
        // 検索結果を取得
        $result = $OrderSearchService->getSearchResult();
        // ページネーションを実施
        $orders = $this->setPagination($result);
        // モールを取得
        $malls = Mall::getAll()->with('order_categories')->get();
        // 倉庫を取得
        $bases = Base::getAll()->get();
        // 受注区分を取得
        $order_categories = OrderCategory::getAll()->get();
        // 運送会社を取得
        $delivery_companies = DeliveryCompany::getAll()->with('shipping_methods')->get();
        // 都道府県を取得
        $prefectures = Prefecture::getAll()->get();
        // 配送地域(国内/海外)を取得
        $ship_region_types = ShipRegionTypeEnum::SHIP_REGION_TYPE_LIST;
        return view('shipping.shipping_history.index')->with([
            'orders' => $orders,
            'malls' => $malls,
            'bases' => $bases,
            'order_categories' => $order_categories,
            'delivery_companies' => $delivery_companies,
            'prefectures' => $prefectures,
            'ship_region_types' => $ship_region_types,
        ]);
    }
}