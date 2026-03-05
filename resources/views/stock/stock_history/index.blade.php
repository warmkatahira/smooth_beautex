<x-app-layout>
    <div class="flex flex-row my-3">
        <x-stock.stock-history.operation-div />
        <x-pagination :pages="$stock_histories" />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-stock.stock-history.search route="stock_history.index" :stockHistoryCategories="$stock_history_categories" />
        <x-stock.stock-history.list :stockHistories="$stock_histories" />
    </div>
</x-app-layout>