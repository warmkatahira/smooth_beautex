<x-app-layout>
    <div class="flex flex-row my-3">
        <x-item.item-upload.operation-div />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-item.item-upload.list :itemUploadHistories="$item_upload_histories" />
    </div>
</x-app-layout>
@vite(['resources/js/item/item_upload/item_upload.js'])