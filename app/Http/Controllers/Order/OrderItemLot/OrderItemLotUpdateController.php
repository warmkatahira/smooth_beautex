<?php

namespace App\Http\Controllers\Order\OrderItemLot;

use App\Http\Controllers\Controller;
// リクエスト
use App\Http\Requests\Order\OrderItemLot\OrderItemLotUpdateRequest;
// サービス
use App\Services\Order\OrderItemLot\OrderItemLotUpdateService;
// その他
use Illuminate\Support\Facades\DB;

class OrderItemLotUpdateController extends Controller
{
    public function update(OrderItemLotUpdateRequest $request)
    {
        // インスタンス化
        $OrderItemLotUpdateService = new OrderItemLotUpdateService;
        // 受注商品LOTを取得
        $order_item_lots = $OrderItemLotUpdateService->getOrderItemLot($request->lots);
        // 更新できる受注であるかチェック
        $OrderItemLotUpdateService->validateOrderItemLotEditable($order_item_lots);
        try {
            DB::transaction(function () use ($request, $OrderItemLotUpdateService, $order_item_lots) {
                // 受注商品LOTを更新
                $OrderItemLotUpdateService->updateOrderItemLot($request, $order_item_lots);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '出荷検品ロットを更新しました。',
        ]);
    }
}