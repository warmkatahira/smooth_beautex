<x-app-layout>
    <div class="flex flex-row my-3">
        <x-shipping.shipping-work-end.operation-div />
    </div>
    <div class="flex flex-row gap-5 mt-5">
        <x-shipping.shipping-work-end.list :shippingWorkEndInfoArr="$shipping_work_end_info_arr" />
    </div>
</x-app-layout>
@vite(['resources/js/shipping/shipping_work_end/shipping_work_end.js'])