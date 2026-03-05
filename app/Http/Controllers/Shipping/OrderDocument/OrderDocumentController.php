<?php

namespace App\Http\Controllers\Shipping\OrderDocument;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\ShippingMethod;
// サービス
use App\Services\Shipping\OrderDocument\OrderDocumentService;

class OrderDocumentController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '個別帳票']);
        // インスタンス化
        $OrderDocumentService = new OrderDocumentService;
        // 出力内容を取得
        $orders = $OrderDocumentService->getIssueOrder($request->shipping_method_id, null, null);
        // 分割情報を取得
        $ranges = $OrderDocumentService->getIssueRange($orders, $request->shipping_method_id);
        // 配送方法を取得
        $shipping_method = ShippingMethod::getSpecify($request->shipping_method_id)->first();
        return view('shipping.order_document.index')->with([
            'ranges' => $ranges,
            'shipping_method' => $shipping_method,
        ]);
    }
}