<x-app-layout>
    <div class="flex flex-row my-3">
        <x-system-admin.user.operation-div />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-system-admin.user.list :users="$users" :roles="$roles" :companies="$companies" />
    </div>
</x-app-layout>