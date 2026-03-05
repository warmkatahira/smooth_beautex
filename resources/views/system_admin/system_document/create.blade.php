<x-app-layout>
    <!-- 操作ボタン -->
    <x-page-back :url="route('system_document.index')" />
    <div class="mt-5">
        <form method="POST" action="{{ route('system_document_create.create') }}" id="system_document_create_form" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col gap-3 my-5">
                <x-form.file-select />
                <x-form.input type="tel" label="並び順" id="sort_order" name="sort_order" :value="null" required="true" />
                <x-form.select-boolean label="社内資料" id="is_internal" name="is_internal" :value="null" required="true" />
            </div>
            <button type="button" id="system_document_create_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto"><i class="las la-check la-lg mr-1"></i>追加</button>
        </form>
    </div>
</x-app-layout>
@vite(['resources/js/system_admin/system_document/system_document.js'])