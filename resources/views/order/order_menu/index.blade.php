<x-app-layout>
    <div class="grid grid-cols-12 gap-5 mt-5">
        @can('warm_check')
            <x-menu.button route="order_import.index" title="受注取込" content="受注データの取り込み" />
        @endcan
        <x-menu.button route="order_mgt.index" title="受注管理" content="出荷作業前の受注の操作" />
    </div>
</x-app-layout>