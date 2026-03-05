<?php

namespace App\Http\Controllers\Order\OrderMgt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Order;
use App\Models\Base;
use App\Models\OrderCategory;
use App\Models\DeliveryCompany;
use App\Models\Prefecture;
// サービス
use App\Services\Order\OrderMgt\OrderMgtService;
use App\Services\Order\OrderMgt\OrderSearchService;
use App\Services\Order\OrderAllocate\OrderAllocateService;

class OrderMgtController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '受注管理']);
        // インスタンス化
        $OrderMgtService = new OrderMgtService;
        $OrderSearchService = new OrderSearchService;
        // セッションを削除
        $OrderSearchService->deleteSession();
        // セッションに検索条件を格納
        $OrderSearchService->setSearchCondition($request);
        // 検索結果を取得
        $result = $OrderSearchService->getSearchResult();
        // ページネーションを実施
        $orders = $OrderSearchService->setPagination($result);
        // 表示する注文ステータス毎の情報を取得
        $disp_statuses = $OrderMgtService->getDispStatusInfo();
        // 倉庫を取得
        $bases = Base::getAll()->get();
        // 受注区分を取得
        $order_categories = OrderCategory::getAll()->get();
        // 運送会社を取得
        $delivery_companies = DeliveryCompany::getAll()->with('shipping_methods')->get();
        // 受注マークを取得
        $order_marks = Order::getOrderMarkFilter($result->get()->select('order_mark'));
        // 都道府県を取得
        $prefectures = Prefecture::getAll()->get();
        return view('order.order_mgt.index')->with([
            'orders' => $orders,
            'disp_statuses' => $disp_statuses,
            'bases' => $bases,
            'order_categories' => $order_categories,
            'delivery_companies' => $delivery_companies,
            'order_marks' => $order_marks,
            'prefectures' => $prefectures,
        ]);
    }

    public function allocate()
    {
        // インスタンス化
        $OrderAllocateService = new OrderAllocateService;
        // 引当処理
        $alert = $OrderAllocateService->procOrderAllocate(null);
        return redirect()->back()->with([
            'alert_type' => $alert['type'],
            'alert_message' => $alert['message'],
        ]);
    }
}