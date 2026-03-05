<?php

namespace App\Http\Controllers\Setting\OrderCategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\OrderCategory;

class OrderCategoryController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '受注区分']);
        // 受注区分を取得
        $order_categories = OrderCategory::getAll()->with('shipper')->get();
        return view('setting.order_category.index')->with([
            'order_categories' => $order_categories,
        ]);
    }
}