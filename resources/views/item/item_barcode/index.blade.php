<x-document-layout>
    <div class="page-container">
        {{-- ヘッダー --}}
        <div class="text-center">
            <span class="block text-xl">商品バーコード表</span>
            <span class="block text-sm">{{ SystemEnum::CUSTOMER_NAME_JP }}出荷システム</span>
        </div>
        {{-- 商品情報 --}}
        <div class="mt-10 flex flex-col items-center gap-4">
            <span class="text-lg font-bold text-center">{{ $item->item_name }}</span>
            {!! DNS1D::getBarcodeSVG($item->item_jan_code, 'C128', 2.5, 70, 'black') !!}
        </div>
    </div>
</x-document-layout>