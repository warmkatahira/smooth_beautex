<x-app-layout>
    <x-page-back :url="session('back_url_1')" />
    <div class="flex flex-row gap-10 my-5">
        <form method="POST" action="{{ route('stock_update.update') }}" id="stock_update_form">
            @csrf
            <div class="flex flex-col gap-3">
                <x-form.p label="商品コード" :value="$stock->item->item_code" />
                <x-form.p label="商品JANコード" :value="$stock->item->item_jan_code" />
                <x-form.p label="商品名" :value="$stock->item->item_name" />
                <x-form.p label="商品カテゴリ1" :value="$stock->item->item_category_1" />
                <x-form.p label="商品カテゴリ2" :value="$stock->item->item_category_2" />
                <x-form.input type="tel" label="LOT" id="lot" name="lot" :value="$stock->lot" />
                <x-form.input type="tel" label="EXP" id="exp" name="exp" :value="$stock->exp" />
            </div>
            <input type="hidden" name="stock_id" value="{{ $stock->stock_id }}">
            <button type="button" id="stock_update_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto mt-5"><i class="las la-check la-lg mr-1"></i>更新</button>
        </form>
        <div class="bg-white border border-black self-start">
            <p class="bg-black text-white text-center py-3">商品画像</p>
            <div class="p-5">
                <img class="w-40 h-40" src="{{ asset('storage/item_images/'.$stock->item_image_file_name) }}">
            </div>
        </div>
    </div>
</x-app-layout>
@vite(['resources/js/stock/stock/stock.js'])