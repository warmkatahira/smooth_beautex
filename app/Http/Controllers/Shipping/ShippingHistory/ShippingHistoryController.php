<?php

namespace App\Http\Controllers\Shipping\ShippingHistory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\DeliveryCompany;
use App\Models\Base;
use App\Models\OrderCategory;
use App\Models\Prefecture;
// サービス
use App\Services\Order\OrderMgt\OrderSearchService;
use App\Services\Shipping\ShippingHistory\ShippingHistoryService;
// 列挙
use App\Enums\OrderStatusEnum;

class ShippingHistoryController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '出荷履歴']);
        // インスタンス化
        $OrderSearchService = new OrderSearchService;
        $ShippingHistoryService = new ShippingHistoryService;
        // 注文ステータスのパラメータを追加
        $request->merge([
            'order_status_id' => OrderStatusEnum::SHUKKA_ZUMI,
        ]);
        // セッションを削除
        $OrderSearchService->deleteSession();
        // セッションに検索条件を格納
        $OrderSearchService->setSearchCondition($request);
        $ShippingHistoryService->setSearchCondition($request);
        // 検索結果を取得
        $result = $OrderSearchService->getSearchResult();
        // ページネーションを実施
        $orders = $OrderSearchService->setPagination($result);
        // 倉庫を取得
        $bases = Base::getAll()->get();
        // 受注区分を取得
        $order_categories = OrderCategory::getAll()->get();
        // 運送会社を取得
        $delivery_companies = DeliveryCompany::getAll()->with('shipping_methods')->get();
        // 都道府県を取得
        $prefectures = Prefecture::getAll()->get();
        return view('shipping.shipping_history.index')->with([
            'orders' => $orders,
            'bases' => $bases,
            'order_categories' => $order_categories,
            'delivery_companies' => $delivery_companies,
            'prefectures' => $prefectures,
        ]);
    }
}