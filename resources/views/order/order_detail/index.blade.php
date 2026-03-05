<x-app-layout>
    <div class="flex flex-row">
        <x-page-back :url="session('back_url_1')" />
        @can('shipping_inspection_actual_deletable_check', $order)
            <form method="POST" action="{{ route('shipping_inspection_actual_delete.delete') }}" id="shipping_inspection_actual_delete_form" class="m-0 ml-auto">
                @csrf
                <button type="button" id="shipping_inspection_actual_delete" class="btn bg-theme-main px-5 py-3.5">出荷検品実績削除</button>
                <input type="hidden" name="order_control_id" value="{{ $order->order_control_id }}">
            </form>
        @endcan
    </div>
    <div class="flex flex-col gap-y-5 bg-white shadow-lg rounded-lg p-5 mt-3">
        <x-order.order-detail.order-info :order="$order" />
        <x-order.order-detail.ship-info :order="$order" />
        <x-order.order-detail.shipping-info :order="$order" />
        <x-order.order-detail.other-info :order="$order" />
        <x-order.order-detail.item-info :order="$order" />
    </div>
</x-app-layout>
<x-order.order-detail.modal.shipping-method-update-modal :order="$order" :deliveryCompanies="$delivery_companies" />
<x-order.order-detail.modal.shipping-base-update-modal :order="$order" :bases="$bases" />
<x-order.order-detail.modal.tracking-no-update-modal :order="$order" />
<x-order.order-detail.modal.order-memo-update-modal :order="$order" />
<x-order.order-detail.modal.shipping-work-memo-update-modal :order="$order" />
<x-order.order-detail.modal.desired-delivery-date-update-modal :order="$order" />
@vite(['resources/js/order/order_detail/order_detail.js'])