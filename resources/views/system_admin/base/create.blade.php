<x-app-layout>
    <x-page-back :url="route('base.index')" />
    <div class="mt-5">
        <form method="POST" action="{{ route('base_create.create') }}" id="base_create_form">
            @csrf
            <div class="flex flex-col gap-3 my-5">
                <x-form.input type="text" label="倉庫ID" id="base_id" name="base_id" :value="null" required="true" />
                <x-form.input type="text" label="倉庫名" id="base_name" name="base_name" :value="null" required="true" />
                <x-form.input type="text" label="ミエルカスタマーコード" id="mieru_customer_code" name="mieru_customer_code" :value="null" required="true" />
                <x-form.input type="text" label="並び順" id="sort_order" name="sort_order" :value="null" required="true" />
            </div>
            <button type="button" id="base_create_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto"><i class="las la-check la-lg mr-1"></i>追加</button>
        </form>
    </div>
</x-app-layout>
@vite(['resources/js/system_admin/base/base.js'])