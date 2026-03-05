<?php

namespace App\Http\Controllers\Order\OrderDetail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Order;
use App\Models\Base;
use App\Models\DeliveryCompany;
use App\Services\Common\MieruService;

class OrderDetailController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '受注詳細']);
        // 受注を取得
        $order = Order::getSpecifyByOrderControlId($request->order_control_id)->with('order_items.item')->first();
        // 倉庫を取得
        $bases = Base::getAll()->get();
        // 運送会社を取得
        $delivery_companies = DeliveryCompany::getAll()->get();
        return view('order.order_detail.index')->with([
            'order' => $order,
            'bases' => $bases,
            'delivery_companies' => $delivery_companies,
        ]);
    }
}