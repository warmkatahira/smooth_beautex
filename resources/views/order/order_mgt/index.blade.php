<x-app-layout>
    <div class="flex flex-row gap-10 mt-3">
        <x-order.order-mgt.status :dispStatuses="$disp_statuses" />
    </div>
    <div class="flex flex-row my-3">
        @can('warm_check')
            <x-order.order-mgt.operation-div />
        @endcan
        <x-pagination :pages="$orders" />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-order.order-mgt.search route="order_mgt.index" :bases="$bases" :orderCategories="$order_categories" :deliveryCompanies="$delivery_companies" :orderMarks="$order_marks" :prefectures="$prefectures" />
        <x-order.order-mgt.list :orders="$orders" />
    </div>
</x-app-layout>
<x-order.order-mgt.shipping-work-start-modal />
@vite(['resources/js/order/order_mgt/order_mgt.js'])