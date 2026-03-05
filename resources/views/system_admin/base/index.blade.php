<x-app-layout>
    <div class="flex flex-row my-3">
        <x-system-admin.base.operation-div />
    </div>
    <x-system-admin.base.list :bases="$bases" />
</x-app-layout>