<div>
    <p class="text-base font-semibold border-b pb-2 mb-4">発送情報</p>
    <div class="flex flex-row gap-5">
        <div class="w-1/2">
            <div class="flex flex-col">
                @can('warm_check')
                    <x-order.order-detail.info-div label="出荷倉庫" :value="$order->base?->base_name" :order="$order" openModalId="shipping_base_update_modal_open" modalTippy="tippy_shipping_base_update" />
                    <x-order.order-detail.info-div label="配送方法" :value="$order->delivery_company_and_shipping_method" :order="$order" openModalId="shipping_method_update_modal_open" modalTippy="tippy_shipping_method_update" />
                @else
                    <x-order.order-detail.info-div label="出荷倉庫" :value="$order->base?->base_name" />
                    <x-order.order-detail.info-div label="配送方法" :value="$order->delivery_company_and_shipping_method" />
                @endcan
            </div>
        </div>
        <div class="w-1/2">
            <div class="flex flex-col">
                @php
                    $desired_delivery_date = $order->desired_delivery_date
                        ? CarbonImmutable::parse($order->desired_delivery_date)->isoFormat('Y年MM月DD日(ddd)')
                        : '';
                @endphp
                <x-order.order-detail.info-div label="配送希望日" :value="$desired_delivery_date" :order="$order" openModalId="desired_delivery_date_update_modal_open" modalTippy="tippy_desired_delivery_date_update" />
                <x-order.order-detail.info-div label="配送希望時間" :value="$order->desired_delivery_time" />
                <x-order.order-detail.tracking-no-info-div :order="$order" />
            </div>
        </div>
    </div>
</div>