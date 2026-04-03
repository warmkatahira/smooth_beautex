<div>
    <p class="text-base font-semibold border-b pb-2 mb-4">検品情報</p>
    <div class="flex flex-row gap-5">
        <div class="w-1/2">
            <div class="flex flex-col">
                <x-order.order-detail.info-div label="出荷検品状態" :value="$order->is_shipping_inspection_complete_text" />
            </div>
        </div>
        <div class="w-1/2">
            <div class="flex flex-col">
                <x-order.order-detail.info-div label="出荷検品完了日時" :value="$order->is_shipping_inspection_complete ? CarbonImmutable::parse($order->shipping_inspection_date)->isoFormat('Y年MM月DD日(ddd) HH:mm:ss') : null" />
            </div>
        </div>
    </div>
</div>