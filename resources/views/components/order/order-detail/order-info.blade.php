<div>
    <p class="text-base font-semibold border-b pb-2 mb-4">注文情報</p>
    <div class="flex flex-row gap-5">
        <div class="w-1/2">
            <div class="flex flex-col">
                <x-order.order-detail.info-div label="注文ステータス" :value="OrderStatusEnum::getJpValueById($order->order_status_id)" />
                <x-order.order-detail.info-div label="取込日時" :value="CarbonImmutable::parse($order->order_import_date . ' ' . $order->order_import_time)->isoFormat('Y年MM月DD日(ddd) HH:mm:ss')" />
                <x-order.order-detail.info-div label="注文番号" :value="$order->order_no" />
            </div>
        </div>
        <div class="w-1/2">
            <div class="flex flex-col">
                <x-order.order-detail.info-div label="注文日" :value="CarbonImmutable::parse($order->order_date)->isoFormat('Y年MM月DD日(ddd)')" />
                <x-order.order-detail.info-div label="受注管理ID" :value="$order->order_control_id" />
                <x-order.order-detail.info-div label="受注区分" :value="$order->order_category->order_category_name" />
            </div>
        </div>
    </div>
</div>