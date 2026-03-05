<?php

namespace App\Http\Controllers\Order\OrderDelete;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Order\OrderDelete\OrderDeleteService;
// その他
use Illuminate\Support\Facades\DB;

class OrderDeleteController extends Controller
{
    public function delete(Request $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $OrderDeleteService = new OrderDeleteService;
                // 削除できる受注であるか確認
                $OrderDeleteService->checkDeletable($request->chk);
                // 引当済みの在庫数を戻す
                $OrderDeleteService->incrementAllocatedStockBackToAvailableStock($request->chk);
                // 受注を削除
                $OrderDeleteService->deleteOrder($request->chk);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => count($request->chk) . '件の受注を削除しました。',
        ]);
    }
}