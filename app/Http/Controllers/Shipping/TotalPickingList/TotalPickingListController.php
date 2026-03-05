<?php

namespace App\Http\Controllers\Shipping\TotalPickingList;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Shipping\TotalPickingList\TotalPickingListCreateService;

class TotalPickingListController extends Controller
{
    public function create()
    {
        try{
            // インスタンス化
            $TotalPickingListCreateService = new TotalPickingListCreateService;
            // 出力内容を取得
            $data = $TotalPickingListCreateService->getCreateItem();
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return view('shipping.document.total_picking_list')->with([
            'items' => $data['items'],
            'report_total_order_quantity' => $data['report_total_order_quantity'],
        ]);
    }
}