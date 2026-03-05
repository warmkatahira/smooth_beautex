<?php

namespace App\Http\Controllers\Shipping\ShippingWorkEndHistory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\ShippingWorkEndHistory;

class ShippingWorkEndHistoryController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '出荷完了履歴']);
        // 出荷完了履歴を取得
        $shipping_work_end_histories = ShippingWorkEndHistory::getDispData()->get();
        return view('shipping.shipping_work_end_history.index')->with([
            'shipping_work_end_histories' => $shipping_work_end_histories,
        ]);
    }
}
