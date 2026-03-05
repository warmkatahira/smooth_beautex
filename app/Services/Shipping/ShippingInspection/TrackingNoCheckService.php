<?php

namespace App\Services\Shipping\ShippingInspection;

// モデル
use App\Models\Order;
// 列挙
use App\Enums\OrderStatusEnum;
// その他
use Illuminate\Support\Facades\DB;

class TrackingNoCheckService
{
    // 配送伝票番号が一致しているか確認
    public function check($request)
    {
        // 送信されてきた問い合わせ番号から不要な文字を取り除く
        $tracking_no = str_replace(['d', 'D', 'a', 'A'], '', $request->tracking_no);
        // 送信されてきた受注管理IDでordersからレコードを取得
        $order = Order::getSpecifyByOrderControlId($request->order_control_id)->where('order_status_id', OrderStatusEnum::SAGYO_CHU)->first();
        // 配送伝票番号が一致しているか
        if($tracking_no != $order->tracking_no){
            return '配送伝票番号が一致しません。';
        }
        // 問題なければnullを返す
        return null;
    }

    // 検品する商品情報を取得
    public function getInspectionTarget($order_control_id)
    {
        // 検品対象の商品情報を取得
        return Order::getSpecifyByOrderControlId($order_control_id)
                    ->where('order_status_id', OrderStatusEnum::SAGYO_CHU)
                    ->join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                    ->join('items', 'items.item_code', 'order_items.order_item_code')
                    ->select(
                        'items.item_id',
                        'items.item_jan_code',
                        'items.item_name',
                        'items.model_jan_code',
                        'items.exp_start_position',
                        'items.lot_1_start_position',
                        'items.lot_1_length',
                        'items.lot_2_start_position',
                        'items.lot_2_length',
                        'items.s_power_code',
                        'items.s_power_code_start_position',
                        'order_items.order_item_id',
                        'order_items.order_quantity',
                    )
                    ->orderBy('items.item_id', 'asc')
                    ->get();
    }

    // セッションに商品情報を格納
    public function setInspectionTarget($inspection_targets)
    {
        // 検品の進捗状況を保持するセッションと配列をクリア
        session()->forget(['progress']);
        session(['lot_result' => array()]);
        $data = [];
        // 商品情報の分だけループ処理
        foreach($inspection_targets as $inspection_target){
            // 商品情報を変数に格納
            $param = [
                'item_id'                       => $inspection_target->item_id,
                'item_jan_code'                 => $inspection_target->item_jan_code,
                'item_name'                     => $inspection_target->item_name,
                'model_jan_code'                => $inspection_target->model_jan_code,
                'exp_start_position'            => $inspection_target->exp_start_position,
                'lot_1_start_position'          => $inspection_target->lot_1_start_position,
                'lot_1_length'                  => $inspection_target->lot_1_length,
                'lot_2_start_position'          => $inspection_target->lot_2_start_position,
                'lot_2_length'                  => $inspection_target->lot_2_length,
                's_power_code'                  => $inspection_target->s_power_code,
                's_power_code_start_position'   => $inspection_target->s_power_code_start_position,
                'order_item_id'                 => $inspection_target->order_item_id,
                'order_quantity'                => $inspection_target->order_quantity,
                'inspection_quantity'           => 0,
                'inspection_complete'           => false,
            ];
            // 配列に格納
            $data[$inspection_target->order_item_id] = $param;
        }
        // セッションへ格納
        session(['progress' => $data]);
    }
}