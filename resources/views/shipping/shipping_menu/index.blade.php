<x-app-layout>
    <div class="grid grid-cols-12 gap-5 mt-5">
        <x-menu.button route="shipping_mgt.index" title="出荷管理" content="出荷作業中の受注の操作" />
        @can('warm_check')
            <x-menu.button route="shipping_inspection.index" title="出荷検品" content="受注毎の出荷検品処理" />
            <x-menu.button route="shipping_work_end.index" title="出荷完了" content="出荷完了状態へ移行する処理" />
            <x-menu.button route="shipping_work_end_history.index" title="出荷完了履歴" content="出荷完了の処理履歴" />
        @endcan
        <x-menu.button route="shipping_history.index" title="出荷履歴" content="出荷が完了した受注の確認" />
    </div>
</x-app-layout>