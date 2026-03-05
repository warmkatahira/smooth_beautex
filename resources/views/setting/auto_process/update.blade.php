<x-app-layout>
    <x-page-back :url="session('back_url_1')" />
    <div class="mt-5">
        <form method="POST" action="{{ route('auto_process_update.update') }}" id="auto_process_update_form">
            @csrf
            <div class="flex flex-col gap-3 my-5">
                <x-form.input type="text" label="自動処理名" id="auto_process_name" name="auto_process_name" :value="$auto_process->auto_process_name" required="true" />
                <x-form.select-array label="アクション区分" id="action_type" name="action_type" :value="$auto_process->action_type" :items="$action_types" required="true" />
                <div id="action_value_text_wrapper">
                    <x-form.input type="text" label="アクション値" id="action_value_text" name="action_value" :value="$auto_process->action_value" required="true" />
                </div>
                <div id="action_value_delivery_company_wrapper">
                    <x-form.select-delivery-company label="アクション値" id="action_value_delivery_company" name="action_value" :deliveryCompanies="$delivery_companies" :value="$auto_process->action_value" />
                </div>
                <div id="action_value_order_item_create_wrapper" class="flex flex-col gap-3">
                    <x-form.input type="text" label="商品コード" id="action_value_order_item_code" name="order_item_code" :value="$auto_process->auto_process_order_item?->order_item_code" required="true" />
                    <x-form.input type="text" label="商品名" id="action_value_order_item_name" name="order_item_name" :value="$auto_process->auto_process_order_item?->order_item_name" required="true" />
                    <x-form.input type="tel" label="出荷数" id="action_value_order_quantity" name="order_quantity" :value="$auto_process->auto_process_order_item?->order_quantity" required="true" />
                </div>
                <x-form.select-array label="条件一致区分" id="condition_match_type" name="condition_match_type" :value="$auto_process->condition_match_type" :items="$condition_match_types" required="true" />
                <x-form.select-boolean label="有効/無効" id="is_active" name="is_active" :value="$auto_process->is_active" required="true" />
                <x-form.input type="tel" label="実行順" id="sort_order" name="sort_order" :value="$auto_process->sort_order" required="true" />
            </div>
            <input type="hidden" name="auto_process_id" value="{{ $auto_process->auto_process_id }}">
            <button type="button" id="auto_process_update_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto"><i class="las la-check la-lg mr-1"></i>更新</button>
        </form>
    </div>
</x-app-layout>
@vite(['resources/js/setting/auto_process/auto_process.js'])