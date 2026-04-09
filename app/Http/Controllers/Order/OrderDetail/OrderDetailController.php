<?php

namespace App\Http\Controllers\Order\OrderDetail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Order;
use App\Models\Base;
use App\Models\DeliveryCompany;
// 列挙
use App\Enums\OrderStatusEnum;

class OrderDetailController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '受注詳細']);
        // 受注を取得
        $order = Order::getSpecifyByOrderControlId($request->order_control_id)->with(['order_items.item', 'order_items.order_item_lots'])->first();
        // ロットごとにstocks照合結果を付加
        $order->order_items->each(function ($order_item) use ($order) {
            $order_item->order_item_lots->each(function ($lot) use ($order_item, $order) {
                $lot->is_valid = is_null($lot->lot) || \App\Models\Stock::where('lot', $lot->lot)
                    ->where('exp', $lot->exp)
                    ->where('item_id', $order_item->item?->item_id)
                    ->where('base_id', $order->shipping_base_id)
                    ->exists();
            });
        });
        // 確認待ちの原因を判定
        $kakunin_machi_reasons = [];
        // 注文ステータスが「確認待ち」の場合
        if($order->order_status_id === OrderStatusEnum::KAKUNIN_MACHI){
            // 商品引当NGがある場合
            if($order->order_items->where('is_item_allocated', 0)->count() >= 1){
                $kakunin_machi_reasons[] = '商品引当NGの商品があります。';
            }
            // 配送方法がNullの場合
            if(is_null($order->shipping_method_id)){
                $kakunin_machi_reasons[] = '配送方法が設定されていません。';
            }
        }
        // 倉庫を取得
        $bases = Base::getAll()->get();
        // 運送会社を取得
        $delivery_companies = DeliveryCompany::getAll()->get();
        return view('order.order_detail.index')->with([
            'order' => $order,
            'bases' => $bases,
            'delivery_companies' => $delivery_companies,
            'kakunin_machi_reasons' => $kakunin_machi_reasons,
        ]);
    }
}