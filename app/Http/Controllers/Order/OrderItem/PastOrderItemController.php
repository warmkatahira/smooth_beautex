<?php

namespace App\Http\Controllers\Order\OrderItem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Order;
// 列挙
use App\Enums\OrderStatusEnum;
// サービス
use App\Services\Order\OrderItem\PastOrderItemService;
// その他
use Illuminate\Support\Facades\DB;

class PastOrderItemController extends Controller
{
    public function search(Request $request)
    {
        // 注文番号を条件に出荷済みの受注を検索
        $orders = Order::where('order_no', 'like', '%'.$request->order_no.'%')
                        ->where('order_status_id', OrderStatusEnum::SHUKKA_ZUMI)
                        ->withCount('order_items')
                        ->get(['order_control_id', 'order_no', 'order_date']);
        return response()->json($orders->map(function($order){
            return [
                'order_control_id'  => $order->order_control_id,
                'order_no'          => $order->order_no,
                'shipping_date'     => $order->shipping_date,
                'order_items_count' => $order->order_items_count,
            ];
        }));
    }

    // 実行
    public function reference(Request $request)
    {
        try {
            DB::transaction(function() use ($request){
                // インスタンス化
                $PastOrderItemService = new PastOrderItemService;
                // 現在の注文の商品を物理削除
                $PastOrderItemService->deleteCurrentOrderItem($request->current_order_control_id);
                // 商品情報を引用
                $PastOrderItemService->referenceOrderItem($request);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '商品情報を引用しました。',
        ]);
    }
}