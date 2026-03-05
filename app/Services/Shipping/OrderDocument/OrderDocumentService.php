<?php

namespace App\Services\Shipping\OrderDocument;

// モデル
use App\Models\Order;

class OrderDocumentService
{
    public function getIssueOrder($shipping_method_id, $start, $end)
    {
        // 指定された出荷グループ × 配送方法の受注を取得
        $orders = Order::where('shipping_group_id', session('search_shipping_group_id'))
                        ->where('orders.shipping_method_id', $shipping_method_id)
                        ->with('shipping_method')
                        ->with('shipping_group')
                        ->with('shipper')
                        ->with('order_items.item')
                        ->select('orders.*')
                        ->orderBy('order_control_id', 'asc');
        // $startがnullでなければ、skipで飛ばして、takeで指定した数を取得
        if(!is_null($start)){
            // skipする数を取得
            $skip = $start - 1;
            // takeする数を取得
            $take = $end - $skip;
            $orders = $orders->skip($skip)->take($take);
        }
        return $orders->get();
    }

    // 分割情報を取得
    public function getIssueRange($orders, $shipping_method_id)
    {
        // 1グループあたりの件数
        $chunk_size = 200;
        // 分割数を計算
        $chunk_count = ceil(count($orders) / $chunk_size);
        // 結果を格納する配列
        $ranges = [];
        // 分割範囲を生成
        for($i = 0; $i < $chunk_count; $i++){
            $start = $i * $chunk_size + 1;
            $end = min(($i + 1) * $chunk_size, count($orders));
            $ranges[] = [
                'shipping_method_id' => $shipping_method_id,
                'start' => $start,
                'end' => $end
            ];
        }
        return $ranges;
    }

    public function getIssueOrderByOrderControlId($order_control_id)
    {
        // 指定された出荷グループ × 配送方法の受注を取得
        return Order::getSpecifyByOrderControlId($order_control_id)
                        ->with('shipping_method')
                        ->with('shipping_group')
                        ->with('shipper')
                        ->with('order_items.item')
                        ->with(['order_items_at_delivery_note.item'])
                        ->select('orders.*')
                        ->get();
    }
}