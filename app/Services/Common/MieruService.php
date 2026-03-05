<?php

namespace App\Services\Common;

// モデル
use App\Models\Order;
use App\Models\Base;
use App\Models\ShippingGroup;
// 列挙
use App\Enums\MieruEnum;
use App\Enums\OrderStatusEnum;
// その他
use Illuminate\Support\Facades\Http;
use Carbon\CarbonImmutable;

class MieruService
{
    // ミエルの進捗を更新する対象を取得
    public function getUpdateProgressTarget($order_control_id)
    {
        // 開発環境の場合は行わない
        if(config('app.env') === 'local'){
            return;
        }
        // 更新を実行する倉庫を格納する配列を初期化
        $mieru_progress_update_arr = [];
        // nullの場合
        if(is_null($order_control_id)){
            // 倉庫を取得
            $bases = Base::getAll()->get();
            // 倉庫の分だけループ処理
            foreach($bases as $base){
                // 更新を実行する倉庫を格納
                $mieru_progress_update_arr[] = [
                    'base_id'           => $base->base_id,
                    'customer_code'     => $base->mieru_customer_code,
                ];
            }
        }
        // nullではない場合
        if(!is_null($order_control_id)){
            // 受注を取得
            $order = Order::getSpecifyByOrderControlId($order_control_id)->first();
            // 更新を実行する倉庫を格納
            $mieru_progress_update_arr[] = [
                'base_id'           => $order->shipping_base_id,
                'customer_code'     => $order->base->mieru_customer_code,
            ];
        }
        // ミエルの進捗を更新
        $this->updateProgress($mieru_progress_update_arr);
    }

    // ミエルの進捗を更新
    public function updateProgress($mieru_progress_update_arr)
    {
        // 現在の日付を取得
        $nowDate = CarbonImmutable::now()->toDateString();
        // 更新を実行する倉庫の分だけループ処理
        foreach($mieru_progress_update_arr as $mieru_progress_update){
            // 出荷PCS数を取得
            $value = ShippingGroup::join('orders', 'shipping_groups.shipping_group_id', 'orders.shipping_group_id')
                        ->join('order_items', 'orders.order_control_id', 'order_items.order_control_id')
                        ->where('shipping_groups.estimated_shipping_date', $nowDate)
                        ->where('shipping_groups.shipping_base_id', $mieru_progress_update['base_id'])
                        ->where('order_status_id', OrderStatusEnum::SAGYO_CHU)
                        ->sum('order_items.order_quantity');
            // 送信
            $this->postProgress($mieru_progress_update['customer_code'], MieruEnum::SHIPMENT_QUANTITY_PCS, $value);
            // 出荷作業中の件数を取得
            $value = ShippingGroup::join('orders', 'orders.shipping_group_id', 'shipping_groups.shipping_group_id')
                            ->where('shipping_groups.estimated_shipping_date', $nowDate)
                            ->where('shipping_groups.shipping_base_id', $mieru_progress_update['base_id'])
                            ->where('order_status_id', OrderStatusEnum::SAGYO_CHU)
                            ->count();
            // 送信
            $this->postProgress($mieru_progress_update['customer_code'], MieruEnum::SHIPMENT_ORDER_QUANTITY, $value);
            // 出荷作業中で未検品の件数を取得
            $value = ShippingGroup::join('orders', 'orders.shipping_group_id', 'shipping_groups.shipping_group_id')
                            ->where('shipping_groups.estimated_shipping_date', $nowDate)
                            ->where('shipping_groups.shipping_base_id', $mieru_progress_update['base_id'])
                            ->where('order_status_id', OrderStatusEnum::SAGYO_CHU)
                            ->where('is_shipping_inspection_complete', 0)
                            ->count();
            // 送信
            $this->postProgress($mieru_progress_update['customer_code'], MieruEnum::INSPECTION_INCOMPLETE_SHIPMENT_ORDER_QUANTITY, $value);
            // 出荷作業中で未検品のPCS数を取得
            $value = ShippingGroup::join('orders', 'shipping_groups.shipping_group_id', 'orders.shipping_group_id')
                        ->join('order_items', 'orders.order_control_id', 'order_items.order_control_id')
                        ->where('shipping_groups.estimated_shipping_date', $nowDate)
                        ->where('shipping_groups.shipping_base_id', $mieru_progress_update['base_id'])
                        ->where('order_status_id', OrderStatusEnum::SAGYO_CHU)
                        ->where('is_shipping_inspection_complete', 0)
                        ->sum('order_items.order_quantity');
            // 送信
            $this->postProgress($mieru_progress_update['customer_code'], MieruEnum::INSPECTION_INCOMPLETE_SHIPMENT_QUANTITY_PCS, $value);
        }
    }

    // 進捗の送信
    public function postProgress($customer_code, $item_code, $value)
    {
        // 送信
        $response = Http::post('https://mieru.warm-sys.com/api/progress_post', [
            'customer_code'     => $customer_code,
            'item_code'         => $item_code,
            'progress_value'    => $value,
            'system_name'       => config('app.name').'_v0.00',
            'pc_name'           => "cloud",
        ]);
    }

    // 出荷確定をミエルに送信
    public function updateShippingConfirmed()
    {
        // 開発環境の場合は行わない
        if(config('app.env') === 'local'){
            return;
        }
       // 倉庫を取得
        $bases = Base::getAll()->get();
        // 倉庫の分だけループ処理
        foreach($bases as $base){
            // 送信
            $response = Http::post('https://mieru.warm-sys.com/api/shipping_confirmed_post', [
                'customer_code' => $base->mieru_customer_code,
            ]);
        }
    }
}