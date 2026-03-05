@props([
    'labelId' => null,
    'label' => null,
    'orderCountId',
    'shipQuantityId',
])

<div class="flex flex-col shadow-xl bg-white p-5 rounded-xl min-w-72">
    <p id="{{ $labelId }}">{{ $label }}</p>
    <div class="flex flex-row justify-between">
        <div class="flex flex-row mt-5">
            <p id="{{ $orderCountId }}" class="font-semibold text-2xl"></p>
            <p class="text-base pt-2 ml-2">件</p>
        </div>
        <div class="flex flex-row mt-5">
            <p id="{{ $shipQuantityId }}" class="font-semibold text-2xl"></p>
            <p class="text-base pt-2 ml-2">PCS</p>
        </div>
    </div>
</div>