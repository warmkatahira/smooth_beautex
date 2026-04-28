<x-app-layout>
    <x-page-back :url="route('order_category.index')" />
    <div class="flex flex-row gap-10 my-5">
        <form method="POST" action="{{ route('order_category_update.update') }}" id="order_category_update_form">
            @csrf
            <div class="flex flex-col border border-gray-400 divide-y divide-gray-400">
                <x-form.input type="text" label="受注区分名" id="order_category_name" name="order_category_name" :value="$order_category->order_category_name" required="true" />
                <x-form.select label="モール" id="mall_id" name="mall_id" :value="$order_category->mall_id" :items="$malls" optionValue="mall_id" optionText="mall_name" required="true" />
                <x-form.select label="荷送人" id="shipper_id" name="shipper_id" :value="$order_category->shipper_id" :items="$shippers" optionValue="shipper_id" optionText="shipper_name" required="true" />
                <x-form.input type="text" label="荷札品名1" id="label_item_name_1" name="label_item_name_1" :value="$order_category->label_item_name_1" required="true" />
                <x-form.input type="tel" label="並び順" id="sort_order" name="sort_order" :value="$order_category->sort_order" required="true" />
            </div>
            <input type="hidden" name="order_category_id" value="{{ $order_category->order_category_id }}">
            <button type="button" id="order_category_update_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto mt-5"><i class="las la-check la-lg mr-1"></i>更新</button>
        </form>
    </div>
</x-app-layout>
@vite(['resources/js/setting/order_category/order_category.js'])