<x-app-layout>
    <x-page-back :url="route('user.index')" />
    <div class="flex flex-row gap-10 mt-5">
        @include('components.system-admin.user.form', [
            'form_mode' => 'create',
        ])
    </div>
</x-app-layout>
@vite(['resources/js/system_admin/user/user.js'])