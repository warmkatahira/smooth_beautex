<x-app-layout>
    <x-page-back :url="session('back_url_1')" />
    <div class="flex flex-row gap-10 my-5">
        <form method="POST" action="{{ route('item_update.update') }}" id="item_update_form" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col gap-3">
                <x-form.image-select label="商品画像" id="item_image_file_name" :required="false" />
                <x-form.p label="商品コード" :value="$item->item_code" />
                <x-form.input type="text" label="商品JANコード" id="item_jan_code" name="item_jan_code" :value="$item->item_jan_code" required="true" />
                <x-form.input type="text" label="商品名" id="item_name" name="item_name" :value="$item->item_name" required="true" />
                <x-form.input type="text" label="商品カテゴリ" id="item_category" name="item_category" :value="$item->item_category" />
                <x-form.input type="text" label="代表JANコード" id="model_jan_code" name="model_jan_code" :value="$item->model_jan_code" />
                <x-form.input type="tel" label="EXP開始位置" id="exp_start_position" name="exp_start_position" :value="$item->exp_start_position" />
                <x-form.input type="tel" label="LOT1開始位置" id="lot_1_start_position" name="lot_1_start_position" :value="$item->lot_1_start_position" />
                <x-form.input type="tel" label="LOT1桁数" id="lot_1_length" name="lot_1_length" :value="$item->lot_1_length" />
                <x-form.input type="tel" label="LOT2開始位置" id="lot_2_start_position" name="lot_2_start_position" :value="$item->lot_2_start_position" />
                <x-form.input type="tel" label="LOT2桁数" id="lot_2_length" name="lot_2_length" :value="$item->lot_2_length" />
                <x-form.input type="tel" label="S-POWERコード" id="s_power_code" name="s_power_code" :value="$item->s_power_code" />
                <x-form.input type="tel" label="S-POWERコード開始位置" id="s_power_code_start_position" name="s_power_code_start_position" :value="$item->s_power_code_start_position" />
                <x-form.select-boolean label="在庫管理" id="is_stock_managed" name="is_stock_managed" :value="$item->is_stock_managed" required="true" />
                <x-form.input type="tel" label="並び順" id="sort_order" name="sort_order" :value="$item->sort_order" required="true" />
            </div>
            <input type="hidden" name="item_id" value="{{ $item->item_id }}">
            <button type="button" id="item_update_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto mt-5"><i class="las la-check la-lg mr-1"></i>更新</button>
        </form>
        <div class="bg-white border border-black self-start">
            <p class="bg-black text-white text-center py-3">商品画像</p>
            <div class="p-5">
                <img class="w-40 h-40" src="{{ asset('storage/item_images/'.$item->item_image_file_name) }}">
            </div>
        </div>
    </div>
</x-app-layout>
@vite(['resources/js/item/item/item.js'])