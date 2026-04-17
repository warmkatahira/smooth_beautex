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
        // 対象の受注商品を取得
        $order_item = OrderItem::findOrFail($request->order_item_id);
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
                // 対象の受注商品を取得
                $order_item = OrderItem::findOrFail($request->order_item_id);
                // 分割数量の配列を取得（例：[8, 8, 4]）
                $quantities = $request->quantities;
                // 先頭の数量で元レコードを更新
                $order_item->shipping_quantity = $quantities[0];
                // 1個口料金オーバーフラグをリセット
                $order_item->is_over_threshold = false;
                $order_item->save();
                // 2件目以降は元レコードを複製して新レコードとして追加
                foreach(array_slice($quantities, 1) as $quantity){
                    // 元レコードを複製
                    $new = $order_item->replicate();
                    // 分割数量をセット
                    $new->shipping_quantity = $quantity;
                    // 1個口料金オーバーフラグをリセット
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