<div>
    <p class="text-base font-semibold border-b pb-2 mb-4">配送先情報</p>
    <div class="flex flex-row gap-5">
        <div class="w-1/2">
            <div class="flex flex-col">
                <x-order.order-detail.info-div label="配送地域" :value="$order->ship_region_type" />
                <x-order.order-detail.info-div label="配送先郵便番号" :value="$order->ship_zip_code" :order="$order" openModalId="ship_zip_code_update_modal_open" modalTippy="tippy_ship_zip_code_update" />
                <x-order.order-detail.info-div label="配送先住所" :value="$order->full_ship_address" :order="$order" openModalId="ship_address_update_modal_open" modalTippy="tippy_ship_address_update" />
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