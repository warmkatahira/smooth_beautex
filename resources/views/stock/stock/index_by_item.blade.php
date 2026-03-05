<x-app-layout>
    <div class="flex flex-row my-3">
        <x-stock.stock.operation-div />
        <x-pagination :pages="$stocks" />
        <x-stock.stock.display-switch />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-stock.stock.search-by-item route="stock.index_by_item" />
        <x-stock.stock.list-by-item :stocks="$stocks" :bases="$bases" />
    </div>
</x-app-layout>
@vite(['resources/js/stock/stock/stock.js'])