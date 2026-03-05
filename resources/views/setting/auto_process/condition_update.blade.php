<x-app-layout>
    <x-page-back :url="session('back_url_1')" />
    <div class="p-6 bg-yellow-100 rounded-lg mt-4 shadow-sm">
        <dl class="space-y-4">
            <x-setting.auto-process.info-div label="自動処理名" :value="$auto_process->auto_process_name" />
            <x-setting.auto-process.info-div label="アクション区分" :value="AutoProcessEnum::getActionTypeJP($auto_process->action_type)" />
            <x-setting.auto-process.info-div label="アクション値" :value="$auto_process->action_value_text" />
            <x-setting.auto-process.info-div label="条件一致区分" :value="AutoProcessEnum::getConditionMatchTypeJP($auto_process->condition_match_type)" />
        </dl>
    </div>
    <div class="flex mt-3">
        <button id="add_condition_btn" type="button" class="btn bg-green-600 text-white p-3 w-32 ml-auto"><i class="las la-plus la-lg mr-1"></i>条件追加</button>
    </div>
    <form method="POST" action="{{ route('auto_process_condition_update.update') }}" id="auto_process_condition_update_form">
        @csrf
        <div id="conditions_wrapper">
            @foreach ($auto_process->auto_process_conditions as $index => $condition)
                <div class="condition_div p-5 bg-white rounded-lg mt-3">
                    <div class="flex justify-between items-center">
                        <p class="text-base">条件{{ $index + 1 }}</p>
                    </div>
                    <div class="flex flex-row text-xs gap-5 mt-3">
                        <div class="flex flex-row w-3/12">
                            <x-setting.auto-process.select label="条件項目" name="column_name" class="column_name" :items="$column_names" :index="$index" :value="$condition->column_name" />
                        </div>
                        <div class="flex flex-row w-4/12">
                            <p class="pt-2 px-5 bg-theme-main text-center border-y border-l border-black w-3/12">条件値</p>
                            <div class="value_text_wrapper w-9/12">
                                <input type="text" name="value[]" class="text-xs w-full value_text" value="{{ $condition->value }}" autocomplete="off">
                            </div>
                            <div class="value_delivery_company_wrapper w-9/12 hidden">
                                <x-setting.auto-process.select-delivery-company :deliveryCompanies="$delivery_companies" :index="$index" :value="$condition->value" />
                            </div>
                        </div>
                        <div class="flex flex-row w-3/12">
                            <x-setting.auto-process.select label="比較方法" name="operator" class="operator" :items="$operators" :index="$index" :value="$condition->operator" />
                        </div>
                    </div>
                </div>
            @endforeach
            @if ($auto_process->auto_process_conditions->isEmpty())
                <div class="condition_div p-5 bg-white rounded-lg mt-3">
                    <div class="flex justify-between items-center">
                        <p class="text-base">条件1</p>
                    </div>
                    <div class="flex flex-row text-xs gap-5 mt-3">
                        <div class="flex flex-row w-3/12">
                            <x-setting.auto-process.select label="条件項目" name="column_name" class="column_name" :items="$column_names" />
                        </div>
                        <div class="flex flex-row w-4/12">
                            <p class="pt-2 px-5 bg-theme-main text-center border-y border-l border-black w-3/12">条件値</p>
                            <div class="value_text_wrapper w-9/12">
                                <input type="text" name="value[]" class="text-xs w-full value_text" autocomplete="off">
                            </div>
                            <div class="value_delivery_company_wrapper w-9/12 hidden">
                                <x-setting.auto-process.select-delivery-company :deliveryCompanies="$delivery_companies" />
                            </div>
                        </div>
                        <div class="flex flex-row w-3/12">
                            <x-setting.auto-process.select label="比較方法" name="operator" class="operator" :items="$operators" />
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <input type="hidden" id="auto_process_id" name="auto_process_id" value="{{ $auto_process->auto_process_id }}">
        <button type="button" id="auto_process_condition_update_enter" class="btn bg-btn-enter p-3 text-white w-56 mt-3"><i class="las la-check la-lg mr-1"></i>設定</button>
    </form>
</x-app-layout>
@vite(['resources/js/setting/auto_process/auto_process_condition.js'])