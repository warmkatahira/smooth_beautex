<?php

namespace App\Services\Order\OrderDetail;

// モデル
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
// 列挙
use App\Enums\OrderStatusEnum;
use App\Enums\ShippingMethodEnum;
// その他
use Illuminate\Support\Facades\DB;

class OrderDetailUpdateService
{
    // 受注をロックして取得
    public function getOrder($request)
    {
        // 対象をロック
        $order = Order::getSpecifyByOrderControlId($request->order_control_id)->lockForUpdate()->first();
        $order_items = OrderItem::where('order_control_id', $request->order_control_id)->lockForUpdate()->get();
        return $order;
    }

    // 出荷倉庫を更新できるか確認
    public function checkUpdatableShippingBase($order)
    {
        // 注文ステータスが「出荷待ち」よりも大きい場合
        if($order->order_status_id > OrderStatusEnum::SHUKKA_MACHI){
            throw new \RuntimeException('出荷倉庫を更新できない注文ステータスです。');
        }
    }

    // 出荷倉庫を更新
    public function updateShippingBase($request, $order)
    {
        // 引当状態と出荷倉庫を更新
        $order->update([
            'is_allocated'      => 0,
            'shipping_base_id'  => $request->shipping_base_id,
        ]);
    }

    // 配送方法を更新できるか確認
    public function checkUpdatableShippingMethod($request, $order)
    {
        // 注文ステータスが「作業中」よりも大きい場合
        if($order->order_status_id > OrderStatusEnum::SAGYO_CHU){
            throw new \RuntimeException('配送方法を更新できない注文ステータスです。');
        }
        // 配送方法をEMSに変更しようとしている場合、注文ステータスが「作業中」以上である場合
        if($request->shipping_method_id == ShippingMethodEnum::SAGAWA_EMS_ID && $order->order_status_id >= OrderStatusEnum::SAGYO_CHU){
            throw new \RuntimeException('配送方法をEMSに更新する場合は、出荷待ちに注文ステータスを戻して下さい。');
        }
    }

    // 配送方法を更新
    public function updateShippingMethod($request, $order)
    {
        // 配送方法と配送伝票番号(Nullへ)を更新
        $order->update([
            'shipping_method_id'    => $request->shipping_method_id,
            'tracking_no'           => null,
        ]);
    }

    // 配送伝票番号を更新できるか確認
    public function checkUpdatableTrackingNo($order)
    {
        // 注文ステータスが「作業中」よりも大きい場合
        if($order->order_status_id > OrderStatusEnum::SAGYO_CHU){
            throw new \RuntimeException('配送伝票番号を更新できない注文ステータスです。');
        }
    }

    // 配送伝票番号を更新
    public function updateTrackingNo($request, $order)
    {
        // 配送伝票番号を更新
        $order->update([
            'tracking_no' => $request->tracking_no,
        ]);
    }

    // 受注マークを更新できるか確認
    public function checkUpdatableOrderMark($order)
    {
        // 注文ステータスが「作業中」よりも大きい場合
        if($order->order_status_id > OrderStatusEnum::SAGYO_CHU){
            throw new \RuntimeException('受注マークを更新できない注文ステータスです。');
        }
    }

    // 受注マークを更新
    public function updateOrderMark($request, $order)
    {
        // 受注マークを更新
        $order->update([
            'order_mark' => $request->order_mark,
        ]);
    }

    // 受注メモを更新できるか確認
    public function checkUpdatableOrderMemo($order)
    {
        // 注文ステータスが「作業中」よりも大きい場合
        if($order->order_status_id > OrderStatusEnum::SAGYO_CHU){
            throw new \RuntimeException('受注メモを更新できない注文ステータスです。');
        }
    }

    // 受注メモを更新
    public function updateOrderMemo($request, $order)
    {
        // 受注メモを更新
        $order->update([
            'order_memo' => $request->order_memo,
        ]);
    }

    // 配送先住所を更新できるか確認
    public function checkUpdatableShipAddress($order)
    {
        // 注文ステータスが「作業中」よりも大きい場合
        if($order->order_status_id > OrderStatusEnum::SAGYO_CHU){
            throw new \RuntimeException('配送先住所を更新できない注文ステータスです。');
        }
    }

    // 配送先住所を更新
    public function updateShipAddress($request, $order)
    {
        // 配送先住所を更新
        $order->update([
            'ship_country_code'     => $request->ship_country_code,
            'ship_province_code'    => $request->ship_province_code,
            'ship_province_name'    => $request->ship_province_name,
            'ship_city'             => $request->ship_city,
            'ship_address_1'        => $request->ship_address_1,
            'ship_address_2'        => $request->ship_address_2,
        ]);
    }

    // 出荷作業メモを更新できるか確認
    public function checkUpdatableShippingWorkMemo($order)
    {
        // 注文ステータスが「作業中」よりも大きい場合
        if($order->order_status_id > OrderStatusEnum::SAGYO_CHU){
            throw new \RuntimeException('出荷作業メモを更新できない注文ステータスです。');
        }
    }

    // 出荷作業メモを更新
    public function updateShippingWorkMemo($request, $order)
    {
        // 出荷作業メモを更新
        $order->update([
            'shipping_work_memo' => $request->shipping_work_memo,
        ]);
    }

    // 補足事項を更新できるか確認
    public function checkUpdatableSupplement($order)
    {
        // 注文ステータスが「作業中」よりも大きい場合
        if($order->order_status_id > OrderStatusEnum::SAGYO_CHU){
            throw new \RuntimeException('補足事項を更新できない注文ステータスです。');
        }
    }

    // 補足事項を更新
    public function updateSupplement($request, $order)
    {
        // 補足事項を更新
        $order->update([
            'supplement' => $request->supplement,
        ]);
    }

    // 配送希望日を更新できるか確認
    public function checkUpdatableDesiredDeliveryDate($order)
    {
        // 注文ステータスが「作業中」よりも大きい場合
        if($order->order_status_id > OrderStatusEnum::SAGYO_CHU){
            throw new \RuntimeException('配送希望日を更新できない注文ステータスです。');
        }
    }

    // 配送希望日を更新
    public function updateDesiredDeliveryDate($request, $order)
    {
        // 配送希望日を更新
        $order->update([
            'desired_delivery_date' => $request->desired_delivery_date,
        ]);
    }

    // 在庫引当処理を更新できるか確認
    public function checkUpdatableIsStockAllocationSkipped($order)
    {
        // 注文ステータスが「出荷待ち」よりも大きい場合
        if($order->order_status_id > OrderStatusEnum::SHUKKA_MACHI){
            throw new \RuntimeException('在庫引当処理を更新できない注文ステータスです。');
        }
        // 再発送ではない場合
        if(!$order->is_redelivery){
            throw new \RuntimeException('再発送の受注ではないため、更新できません。');
        }
    }

    // 在庫引当処理を更新
    public function updateIsStockAllocationSkipped($request, $order)
    {
        // 在庫引当処理を更新
        $order->update([
            'is_stock_allocation_skipped' => $request->is_stock_allocation_skipped,
        ]);
    }

    // 出荷検品処理を更新できるか確認
    public function checkUpdatableIsShippingInspectionSkipped($order)
    {
        // 注文ステータスが「出荷待ち」よりも大きい場合
        if($order->order_status_id > OrderStatusEnum::SHUKKA_MACHI){
            throw new \RuntimeException('出荷検品処理を更新できない注文ステータスです。');
        }
        // 再発送ではない場合
        if(!$order->is_redelivery){
            throw new \RuntimeException('再発送の受注ではないため、更新できません。');
        }
    }

    // 出荷検品処理を更新
    public function updateIsShippingInspectionSkipped($request, $order)
    {
        // 出荷検品処理を更新
        $order->update([
            'is_shipping_inspection_skipped' => $request->is_shipping_inspection_skipped,
        ]);
    }
}