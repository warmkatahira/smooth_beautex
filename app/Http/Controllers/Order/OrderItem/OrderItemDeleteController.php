<?php

namespace App\Http\Controllers\Order\OrderItem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// リクエスト
use App\Http\Requests\Order\OrderItem\OrderItemDeleteRequest;
// サービス
use App\Services\Order\OrderItem\OrderItemDeleteService;
// その他
use Illuminate\Support\Facades\DB;

class OrderItemDeleteController extends Controller
{
    public function delete(OrderItemDeleteRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // インスタンス化
                $OrderItemDeleteService = new OrderItemDeleteService;
                // 受注商品を削除できる注文であるか確認
                $order_item = $OrderItemDeleteService->checkOrderItemDeletable($request->order_item_id);
                // 受注商品を削除
                $OrderItemDeleteService->deleteOrderItem($order_item);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '受注商品を削除しました。',
        ]);
    }
}