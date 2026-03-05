<x-app-layout>
    <x-page-back :url="route('base.index')" />
    <div class="mt-5">
        <form method="POST" action="{{ route('base_update.update') }}" id="base_update_form">
            @csrf
            <div class="flex flex-col gap-3 my-5">
                <x-form.p label="倉庫ID" :value="$base->base_id" />
                <x-form.input type="text" label="倉庫名" id="base_name" name="base_name" :value="$base->base_name" required="true" />
                <x-form.input type="color" label="倉庫カラー" id="base_color_code" name="base_color_code" :value="$base->base_color_code" required="true" />
                <x-form.input type="text" label="ミエルカスタマーコード" id="mieru_customer_code" name="mieru_customer_code" :value="$base->mieru_customer_code" required="true" />
                <x-form.input type="text" label="並び順" id="sort_order" name="sort_order" :value="$base->sort_order" required="true" />
            </div>
            <input type="hidden" name="base_id" value="{{ $base->base_id }}">
            <button type="button" id="base_update_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto"><i class="las la-check la-lg mr-1"></i>更新</button>
        </form>
    </div>
</x-app-layout>
@vite(['resources/js/system_admin/base/base.js'])