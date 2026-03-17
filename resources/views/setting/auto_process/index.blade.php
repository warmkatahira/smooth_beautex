<x-app-layout>
    <div class="flex flex-row my-3">
        <x-setting.auto-process.operation-div />
        <x-pagination :pages="$auto_processes" />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-setting.auto-process.list :autoProcesses="$auto_processes" :actionTypes="$action_types" />
    </div>
</x-app-layout>
@vite(['resources/js/setting/auto_process/auto_process.js'])