<x-app-layout>
    <x-page-back :url="route('base_shipping_method.index')" />
    <div class="flex flex-row gap-10 my-5">
        <form method="POST" action="{{ route('base_shipping_method_update.update') }}" id="base_shipping_method_update_form">
            @csrf
            <div class="flex flex-col gap-3">
                <x-form.p label="倉庫名" :value="$base_shipping_method->base->base_name" />
                <x-form.p label="運送会社" :value="$base_shipping_method->shipping_method->delivery_company->delivery_company" />
                <x-form.p label="配送方法" :value="$base_shipping_method->shipping_method->shipping_method" />
                <x-form.input type="text" label="設定1" id="setting_1" name="setting_1" :value="$base_shipping_method->setting_1" tippy="tippy_setting_1" />
                <x-form.input type="text" label="設定2" id="setting_2" name="setting_2" :value="$base_shipping_method->setting_2" tippy="tippy_setting_2" />
                <x-form.input type="text" label="設定3" id="setting_3" name="setting_3" :value="$base_shipping_method->setting_3" tippy="tippy_setting_3" />
                <x-form.select label="e飛伝バージョン" id="e_hiden_version_id" name="e_hiden_version_id" :value="$base_shipping_method->e_hiden_version_id" :items="$e_hiden_versions" optionValue="e_hiden_version_id" optionText="e_hiden_version" />
            </div>
            <input type="hidden" name="base_shipping_method_id" value="{{ $base_shipping_method->base_shipping_method_id }}">
            <button type="button" id="base_shipping_method_update_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto mt-5"><i class="las la-check la-lg mr-1"></i>更新</button>
        </form>
    </div>
</x-app-layout>
@vite(['resources/js/setting/base_shipping_method/base_shipping_method.js'])