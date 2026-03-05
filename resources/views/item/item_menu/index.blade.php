<x-app-layout>
    <div class="grid grid-cols-12 gap-5 mt-5">
        <x-menu.button route="item.index" title="商品" content="商品情報の確認・ダウンロード" />
        @can('warm_check')
            <x-menu.button route="item_upload.index" title="商品アップロード" content="商品情報の追加・更新" />
            <x-menu.button route="item_qr_analysis.index" title="商品QR解析" content="商品のQRを解析" />
        @endcan
    </div>
</x-app-layout>