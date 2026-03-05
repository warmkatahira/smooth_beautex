<?php

namespace App\Http\Controllers\Shipping\ShippingInspectionActualDelete;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Shipping\ShippingInspectionActualDelete\ShippingInspectionActualDeleteService;
// その他
use Illuminate\Support\Facades\DB;

class ShippingInspectionActualDeleteController extends Controller
{
    public function delete(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // インスタンス化
                $ShippingInspectionActualDeleteService = new ShippingInspectionActualDeleteService;
                // 出荷検品実績を削除できるか確認
                $order = $ShippingInspectionActualDeleteService->checkDeletable($request->order_control_id);
                // 出荷検品実績を削除
                $ShippingInspectionActualDeleteService->deleteShippingInspectionActual($order);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '出荷検品実績を削除しました。',
        ]);
    }
}