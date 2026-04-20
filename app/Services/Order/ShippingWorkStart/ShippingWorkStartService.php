<?php

namespace App\Services\Order\ShippingWorkStart;

// モデル
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingGroup;
// 列挙
use App\Enums\OrderStatusEnum;
use App\Enums\ShippingMethodEnum;

class ShippingWorkStartService
{
    // 選択している対象が出荷開始できるか確認
    public function checkShippingWorkStartable($chk)
    {
        // 対象をロック
        $orders = Order::whereIn('order_control_id', $chk)->with('order_items')->lockForUpdate()->get();
        $order_items = OrderItem::whereIn('order_control_id', $chk)->lockForUpdate()->get();
        // 注文ステータスが「出荷待ち」以外の対象が存在する場合
        if($orders->where('order_status_id', '!=', OrderStatusEnum::SHUKKA_MACHI)->count() > 0){
            throw new \RuntimeException('注文ステータスが出荷待ち以外の受注が選択されています。');
        }
        // 複数の出荷倉庫が存在する場合
        if($orders->pluck('shipping_base_id')->unique()->count() > 1){
            throw new \RuntimeException('複数の出荷倉庫の受注が選択されています。');
        }
        return $orders;
    }

    // 出荷グループを作成
    public function createShippingGroup($request)
    {
        // 出荷倉庫IDを取得するために、先頭のパラメータで受注を取得
        $order = Order::getSpecifyByOrderControlId($request->chk[0])->first();
        // 出荷グループを作成
        return ShippingGroup::create([
            'shipping_group_name'       => $request->shipping_group_name,
            'shipping_base_id'          => $order->shipping_base_id,
            'estimated_shipping_date'   => $request->estimated_shipping_date,
        ]);
    }

    // 出荷グループと注文ステータスを更新
    public function updateShippingWorkStart($chk, $shipping_group_id)
    {
        // 出荷グループと受注ステータスを更新
        return Order::whereIn('order_control_id', $chk)->update([
            'order_status_id'       => OrderStatusEnum::SAGYO_CHU,
            'shipping_group_id'     => $shipping_group_id,
        ]);
    }

    // 配送方法がEMSでUS宛ての場合、出荷個口Noを条件に応じて更新
    public function updatePackageNo($orders)
    {
        // 14,500円でおさまらなかった受注を格納する配列を初期化
        $over_threshold_order_ids = [];
        // 受注の分だけループ処理
        foreach($orders as $order){
            // 配送方法がEMS以外かUS宛て以外の場合
            if($order->shipping_method_id != ShippingMethodEnum::SAGAWA_EMS_ID || $order->ship_country_code != 'US'){
                // 次のループ処理へ
                continue;
            }
            // 出荷数の多い順に並び替え（数量が同じ場合はorder_item_idの昇順）
            $order_items = $order->order_items()
                                ->reorder()
                                ->orderBy('shipping_quantity', 'desc')
                                ->orderBy('order_item_id', 'asc')
                                ->get();
            // 計算に使用する変数を初期化
            $package_no = 1;
            $cumulative = 0;
            // 1個口の閾値を定義(14,500円の意味)
            $threshold = 14500;
            // 受注に紐付いている商品の分だけループ処理
            foreach($order_items as $order_item){
                // 1個口で14,500円をこえた場合のフラグを初期化
                $order_item->is_over_threshold = false;
                // 商品単価を1.6で割り、購入数をかける(小数点以下は切り捨て)
                $price = floor($order_item->order_item_unit_price / 1.6) * $order_item->shipping_quantity;
                // 1商品で閾値を超えている場合
                if($cumulative === 0 && $price > $threshold){
                    // 受注管理IDとフラグを更新
                    $over_threshold_order_ids[] = $order->order_control_id;
                    $order_item->is_over_threshold = true;
                }
                // 累計が0でない場合かつ、この商品を加算すると閾値を超える場合
                if($cumulative > 0 && $cumulative + $price > $threshold){
                    // package_noをカウントアップして、合計商品金額を初期化
                    $package_no++;
                    $cumulative = 0;
                }
                // 出荷個口Noを更新して保存
                $order_item->package_no = $package_no;
                $order_item->save();
                // 合計商品金額の変数に今回の商品単価を加算
                $cumulative += $price;
            }
        }
        return array_unique($over_threshold_order_ids);
    }
}