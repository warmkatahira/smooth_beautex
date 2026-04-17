<?php

namespace App\Http\Controllers\Order\OrderDetail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\OrderItem;
// サービス
use App\Services\Order\OrderDetail\OrderDetailUpdateService;
// その他
use Illuminate\Support\Facades\DB;

class OrderItemSplitController extends Controller
{
    public function split_preview(Request $request)
    {
        $order_item = OrderItem::findOrFail($request->order_item_id);
        $unit = floor($order_item->order_item_unit_price / 1.6);
        $max_quantity = floor(14500 / $unit);

        $quantities = [];
        $remaining = $order_item->shipping_quantity;
        while ($remaining > 0) {
            $q = min($remaining, $max_quantity);
            $quantities[] = [
                'quantity' => $q,
                'subtotal' => $unit * $q,
            ];
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
                $order_item = OrderItem::findOrFail($request->order_item_id);
                $quantities = $request->quantities; // [8, 8, 4] など
                // 先頭は元レコードの数量を更新
                $order_item->shipping_quantity = $quantities[0];
                $order_item->is_over_threshold = false;
                $order_item->save();
                // 2件目以降は新レコードとして追加
                foreach (array_slice($quantities, 1) as $quantity) {
                    $new = $order_item->replicate();
                    $new->shipping_quantity = $quantity;
                    $new->is_over_threshold = false;
                    $new->save();
                }
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