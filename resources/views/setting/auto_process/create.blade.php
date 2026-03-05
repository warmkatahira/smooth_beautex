<x-app-layout>
    <x-page-back :url="session('back_url_1')" />
    <div class="mt-5">
        <form method="POST" action="{{ route('auto_process_create.create') }}" id="auto_process_create_form">
            @csrf
            <div class="flex flex-col gap-3 my-5">
                <x-form.input type="text" label="自動処理名" id="auto_process_name" name="auto_process_name" :value="null" required="true" />
                <x-form.select-array label="アクション区分" id="action_type" name="action_type" :value="null" :items="$action_types" required="true" />
                <div id="action_value_text_wrapper">
                    <x-form.input type="text" label="アクション値" id="action_value_text" name="action_value" :value="null" required="true" />
                </div>
                <div id="action_value_delivery_company_wrapper">
                    <x-form.select-delivery-company label="アクション値" id="action_value_delivery_company" name="action_value" :deliveryCompanies="$delivery_companies" :value="null" />
                </div>
                <div id="action_value_order_item_create_wrapper" class="flex flex-col gap-3">
                    <x-form.input type="text" label="商品コード" id="action_value_order_item_code" name="order_item_code" :value="null" required="true" />
                    <x-form.input type="text" label="商品名" id="action_value_order_item_name" name="order_item_name" :value="null" required="true" />
                    <x-form.input type="tel" label="出荷数" id="action_value_order_quantity" name="order_quantity" :value="null" required="true" />
                </div>
                <x-form.select-array label="条件一致区分" id="condition_match_type" name="condition_match_type" :value="null" :items="$condition_match_types" required="true" />
                <x-form.select-boolean label="有効/無効" id="is_active" name="is_active" :value="null" required="true" />
                <x-form.input type="tel" label="実行順" id="sort_order" name="sort_order" :value="null" required="true" />
            </div>
            <button type="button" id="auto_process_create_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto"><i class="las la-check la-lg mr-1"></i>追加</button>
        </form>
    </div>
</x-app-layout>
@vite(['resources/js/setting/auto_process/auto_process.js'])