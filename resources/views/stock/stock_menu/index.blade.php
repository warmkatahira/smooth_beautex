<x-app-layout>
    <div class="grid grid-cols-12 gap-5 mt-5">
        <x-menu.button route="stock.index_by_item" title="在庫" content="在庫情報の確認・ダウンロード" />
        @can('warm_check')
            <x-menu.button route="receiving_inspection.index" title="入庫検品" content="入庫検品の処理" />
            <x-menu.button route="input_stock_operation.index" title="入力在庫数操作" content="入力による在庫数の増減処理" />
            <x-menu.button route="stock_history.index" title="在庫履歴" content="在庫数の増減履歴" />
        @endcan
    </div>
</x-app-layout>