<div>
    <p class="text-base font-semibold border-b pb-2 mb-4">その他情報</p>
    <div class="flex flex-row gap-5">
        <div class="w-1/2">
            <div class="flex flex-col">
                @can('warm_check')
                    <x-order.order-detail.info-div label="受注メモ" :value="$order->order_memo" :order="$order" openModalId="order_memo_update_modal_open" infoTippy="tippy_order_memo" modalTippy="tippy_order_memo_update" />
                    <x-order.order-detail.info-div label="出荷作業メモ" :value="$order->shipping_work_memo" :order="$order" openModalId="shipping_work_memo_update_modal_open" infoTippy="tippy_shipping_work_memo" modalTippy="tippy_shipping_work_memo_update" />
                @endcan
            </div>
        </div>
    </div>
</div>