<?php

namespace App\Http\Controllers\Order\OrderItem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Item;
// リクエスト
use App\Http\Requests\Order\OrderItem\OrderItemCreateRequest;
// サービス
use App\Services\Order\OrderItem\OrderItemCreateService;
// その他
use Illuminate\Support\Facades\DB;

class OrderItemCreateController extends Controller
{
    public function search(Request $request)
    {
        // 検索キーワードを取得
        $q = $request->q;
        // 商品コード・商品名・JANコードで部分一致検索し、最大50件取得
        $items = Item::where('item_code', 'like', "%{$q}%")
                    ->orWhere('item_name', 'like', "%{$q}%")
                    ->orWhere('item_jan_code', 'like', "%{$q}%")
                    ->limit(50)
                    ->get(['item_id', 'item_code', 'item_name', 'item_jan_code']);
        // JSON形式で返却
        return response()->json($items);
    }

    public function create(OrderItemCreateRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // インスタンス化
                $OrderItemCreateService = new OrderItemCreateService;
                // 受注商品を追加できる注文であるか確認
                $order = $OrderItemCreateService->checkOrderItemCreatable($request->order_control_id);
                // 受注商品を追加
                $OrderItemCreateService->createOrderItem($request);
                // 注文ステータスを変更
                $OrderItemCreateService->updateOrderStatus($order);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '受注商品を追加しました。',
        ]);
    }
}