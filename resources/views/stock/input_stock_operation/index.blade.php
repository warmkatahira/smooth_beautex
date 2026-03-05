<x-app-layout>
    <div class="flex flex-row my-3">
        <x-stock.input-stock-operation.operation-div />
        <x-pagination :pages="$stocks" />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-stock.stock.search-by-stock route="input_stock_operation.index" :bases="$bases" />
        <x-stock.input-stock-operation.list :stocks="$stocks" :bases="$bases" />
    </div>
</x-app-layout>
@vite(['resources/js/stock/input_stock_operation/input_stock_operation.js'])