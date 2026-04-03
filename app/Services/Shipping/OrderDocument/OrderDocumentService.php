<?php

namespace App\Services\Shipping\OrderDocument;

// モデル
use App\Models\Order;

class OrderDocumentService
{
    // 出力内容を取得
    public function getIssueOrder($shipping_method_id, $start, $end)
    {
        // 指定された出荷グループ × 配送方法の受注を取得
        $orders = Order::where('shipping_group_id', session('filter_shipping_group_id'))
                    ->where('orders.shipping_method_id', $shipping_method_id)
                    ->with(['shipping_method', 'shipping_group', 'order_items.item', 'order_category.mall'])
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
        // order_control_id × package_no の数だけ展開
        return $orders->get()->flatMap(function ($order) {
            $packageNos = $order->order_items->pluck('package_no')->unique()->sort()->values();
            // package_noが1種類以下はそのまま返す
            if($packageNos->count() <= 1){
                $order->package_no = $packageNos->first();
                $order->package_no_index = 1;
                $order->package_no_total = 1;
                return [$order];
            }
            // package_noの数だけOrderを複製して返す
            return $packageNos->map(function ($packageNo) use ($order, $packageNos) {
                $cloned = clone $order;
                $cloned->package_no = $packageNo;
                // 何分の何かを設定
                $cloned->package_no_index = $packageNos->search($packageNo) + 1;
                $cloned->package_no_total = $packageNos->count();
                $cloned->setRelation(
                    'order_items',
                    $order->order_items->where('package_no', $packageNo)->values()
                );
                return $cloned;
            });
        });
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
}