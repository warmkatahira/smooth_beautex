<x-app-layout>
    <div class="grid grid-cols-12 gap-5 mt-5">
        <x-menu.button route="shipping_base.index" title="出荷倉庫" content="出荷倉庫の設定" />
        <x-menu.button route="base_shipping_method.index" title="倉庫別配送方法" content="倉庫別配送方法の設定" />
        <x-menu.button route="shipper.index" title="荷送人" content="荷送人の設定" />
        <x-menu.button route="order_category.index" title="受注区分" content="受注区分の設定" />
        <x-menu.button route="auto_process.index" title="自動処理" content="自動処理のの設定" />
    </div>
</x-app-layout>