<x-app-layout>
    <div class="flex flex-row mt-2">
        @can('admin_check')
            <!-- 操作用ボタン -->
            <x-system-admin.system-document.operation-div />
        @endcan
    </div>
    <div class="flex flex-row items-start mt-3">
        <!-- 一覧 -->
        <x-system-admin.system-document.list :systemDocuments="$system_documents" />
    </div>
</x-app-layout>
@vite(['resources/js/system_admin/system_document/system_document.js'])