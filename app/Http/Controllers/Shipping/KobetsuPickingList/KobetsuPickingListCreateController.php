<?php

namespace App\Http\Controllers\Shipping\KobetsuPickingList;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Shipping\OrderDocument\OrderDocumentService;

class KobetsuPickingListCreateController extends Controller
{
    public function create(Request $request)
    {
        try{
            // インスタンス化
            $OrderDocumentService = new OrderDocumentService;
            // 出力内容を取得
            $orders = $OrderDocumentService->getIssueOrder($request->shipping_method_id, $request->start, $request->end);
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return view('shipping.document.kobetsu_picking_list')->with([
            'orders' => $orders,
        ]);
    }

    public function create_specify_order(Request $request)
    {
        try{
            // インスタンス化
            $OrderDocumentService = new OrderDocumentService;
            // 出力内容を取得
            $orders = $OrderDocumentService->getIssueOrderByOrderControlId($request->order_control_id);
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return view('shipping.document.kobetsu_picking_list')->with([
            'orders' => $orders,
        ]);
    }
}