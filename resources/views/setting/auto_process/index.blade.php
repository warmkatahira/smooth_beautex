<x-app-layout>
    <div class="flex flex-row my-3">
        <x-setting.auto-process.operation-div />
        <x-pagination :pages="$auto_processes" />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-setting.auto-process.search route="auto_process.index" :actionTypes="$action_types" />
        <x-setting.auto-process.list :autoProcesses="$auto_processes" />
    </div>
</x-app-layout>
@vite(['resources/js/setting/auto_process/auto_process.js'])