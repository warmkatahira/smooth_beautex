<?php

namespace App\Http\Controllers\Order\OrderItemSplit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\OrderItem;
// サービス
use App\Services\Order\OrderItemSplit\OrderItemSplitService;
use App\Services\Order\ShippingWorkStart\ShippingWorkStartService;
// その他
use Illuminate\Support\Facades\DB;

class OrderItemSplitController extends Controller
{
    public function split_preview(Request $request)
    {
        // 対象の受注商品を取得
        $order_item = OrderItem::getSpecify($request->order_item_id)->first();
        // 単価を1.6で割り、小数点以下を切り捨て
        $unit = floor($order_item->order_item_unit_price / 1.6);
        // 1個口に収まる最大数量を計算（14,500円 ÷ 単価）
        $max_quantity = floor(14500 / $unit);
        // 分割数量を格納する配列を初期化
        $quantities = [];
        // 残数量を初期化
        $remaining = $order_item->shipping_quantity;
        // 残数量がなくなるまでループ
        while($remaining > 0){
            // 残数量と最大数量の小さい方を今回の数量とする
            $q = min($remaining, $max_quantity);
            // 数量と小計を配列に追加
            $quantities[] = [
                'quantity' => $q,
                'subtotal' => $unit * $q,
            ];
            // 残数量を減算
            $remaining -= $q;
        }
        return response()->json([
            'order_item_id' => $order_item->order_item_id,
            'quantities'    => $quantities,
        ]);
    }

    public function split(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // インスタンス化
                $OrderItemSplitService = new OrderItemSplitService;
                $ShippingWorkStartService = new ShippingWorkStartService;
                // 指定された受注商品を分割
                $orders = $OrderItemSplitService->splitOrderItem($request);
                // 出荷個口Noを条件に応じて更新
                $ShippingWorkStartService->updatePackageNo($orders);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '受注商品分割を実行しました。',
        ]);
    }
}