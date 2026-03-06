<x-app-layout>
    <x-page-back :url="session('back_url_1')" />
    <div class="mt-5 flex flex-row">
        @include('components.setting.auto-process.form', [
            'form_mode' => 'create',
        ])
    </div>
</x-app-layout>
@vite(['resources/js/setting/auto_process/auto_process.js'])