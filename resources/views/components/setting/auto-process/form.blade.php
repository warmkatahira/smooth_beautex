<form method="POST"
      action="{{ $form_mode === 'create'
                    ? route('auto_process_create.create')
                    : route('auto_process_update.update') }}"
      id="auto_process_form">
    @csrf
    <div class="flex flex-col border border-gray-400 divide-y divide-gray-400">
        <x-form.input type="text" label="自動処理名" id="auto_process_name" name="auto_process_name" :value="$form_mode === 'update' ? $auto_process->auto_process_name : null" required="true" />
        <x-form.select-array label="アクション区分" id="action_type" name="action_type" :value="$form_mode === 'update' ? $auto_process->action_type : null" :items="$action_types" required="true" />
        <div id="action_value_text_wrapper">
            <x-form.input type="text" label="アクション値" id="action_value_text" name="action_value" :value="$form_mode === 'update' ? $auto_process->action_value : null" required="true" />
        </div>
        <div id="action_value_delivery_company_wrapper">
            <x-form.select-delivery-company label="配送方法" id="action_value_delivery_company" name="action_value" :deliveryCompanies="$delivery_companies" :value="$form_mode === 'update' ? $auto_process->action_value : null" />
        </div>
        <div id="action_value_desired_delivery_time_wrapper">
            <x-form.select-array label="配送希望時間" id="action_value_desired_delivery_time" name="action_value" :items="$time_zones" :value="$form_mode === 'update' ? $auto_process->action_value : null" required="true" />
        </div>
        <div id="action_value_shipping_base_id_wrapper">
            <x-form.select label="出荷倉庫" id="action_value_shipping_base_id" name="action_value" :value="$form_mode === 'update' ? $auto_process->action_value : null" :items="$bases" optionValue="base_id" optionText="base_name" required="true" />
        </div>
        <div id="action_value_order_item_create_wrapper" class="flex flex-col divide-y divide-gray-400">
            <x-form.input type="text" label="商品ID" id="action_value_order_item_id" name="order_item_id" :value="$form_mode === 'update' ? $auto_process->auto_process_order_item?->order_item_id : null" required="true" />
            <x-form.input type="tel" label="出荷数" id="action_value_shipping_quantity" name="shipping_quantity" :value="$form_mode === 'update' ? $auto_process->auto_process_order_item?->shipping_quantity : null" required="true" />
        </div>
        <x-form.select-array label="条件一致区分" id="condition_match_type" name="condition_match_type" :value="$form_mode === 'update' ? $auto_process->condition_match_type : null" :items="$condition_match_types" required="true" />
        <x-form.select-boolean label="有効/無効" id="is_active" name="is_active" :value="$form_mode === 'update' ? $auto_process->is_active : null" label0="無効" label1="有効" required="true" />
        <x-form.input type="tel" label="実行順" id="sort_order" name="sort_order" :value="$form_mode === 'update' ? $auto_process->sort_order : null" required="true" />
    </div>
    @if($form_mode === 'update')
        <input type="hidden" name="auto_process_id" value="{{ $auto_process->auto_process_id }}">
    @endif
    <button type="button" id="auto_process_{{ $form_mode }}_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto mt-3"><i class="las la-check la-lg mr-1"></i>{{ $form_mode === 'create' ? '追加' : '更新' }}</button>
</form>