<x-app-layout>
    <div class="flex flex-row my-3">
        <x-shipping.shipping-history.operation-div />
        <x-pagination :pages="$orders" />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-shipping.shipping-history.list :orders="$orders" :malls="$malls" :bases="$bases" :deliveryCompanies="$delivery_companies" :prefectures="$prefectures" :shipRegionTypes="$ship_region_types" />
    </div>
</x-app-layout>