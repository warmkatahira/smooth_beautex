<x-app-layout>
    <div class="flex flex-row my-3">
        <x-order.order-import.operation-div />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-order.order-import.list :orderImportHistories="$order_import_histories" />
    </div>
</x-app-layout>
@vite(['resources/js/order/order_import/order_import.js'])