<div>
    <p class="text-base font-semibold border-b pb-2 mb-4">配送先情報</p>
    <div class="flex flex-row gap-5">
        <div class="w-1/2">
            <div class="flex flex-col">
                <x-order.order-detail.info-div label="配送先郵便番号" :value="$order->ship_zip_code" />
                <x-order.order-detail.info-div label="配送先住所" :value="$order->ship_address" />
            </div>
        </div>
        <div class="w-1/2">
            <div class="flex flex-col">
                <x-order.order-detail.info-div label="配送先名" :value="$order->ship_name" />
                <x-order.order-detail.info-div label="配送先電話番号" :value="$order->ship_tel" />
            </div>
        </div>
    </div>
</div>