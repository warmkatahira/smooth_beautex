<x-app-layout>
    <div class="flex flex-row my-3">
        <x-system-admin.operation-log.operation-div />
        <x-pagination :pages="$operation_logs" />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-system-admin.operation-log.search route="operation_log.index" />
        <x-system-admin.operation-log.list :operationLogs="$operation_logs" />
    </div>
</x-app-layout>