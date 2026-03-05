<x-app-layout>
    <div class="flex flex-row my-3">
        <x-item.item.operation-div />
        <x-pagination :pages="$items" />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-item.item.search route="item.index" />
        <x-item.item.list :items="$items" />
    </div>
</x-app-layout>
@vite(['resources/js/item/item/item.js'])