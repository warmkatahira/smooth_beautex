<x-app-layout>
    <x-shipping.shipping-mgt.shipping-group-select :shippingGroups="$shipping_groups" :shippingMethods="$shipping_methods" />
    <div class="flex flex-row my-3">
        @can('warm_check')
            <x-shipping.shipping-mgt.operation-div />
        @endcan
        <x-pagination :pages="$orders" />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-shipping.shipping-mgt.list :orders="$orders" :malls="$malls" :bases="$bases" :deliveryCompanies="$delivery_companies" :prefectures="$prefectures" :shipRegionTypes="$ship_region_types" />
    </div>
</x-app-layout>
@if(!is_null($shipping_group))
    <x-shipping.shipping-mgt.shipping-group-update-modal :shippingGroup="$shipping_group" />
@endif
@vite(['resources/js/shipping/shipping_mgt/shipping_mgt.js'])