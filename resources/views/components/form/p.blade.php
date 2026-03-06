@props([
    'label',
    'value',
])

<div class="flex flex-col bg-white py-2 px-3 w-form-div">
    <label class="text-gray-800 py-2.5 pl-3">
        {{ $label }}
    </label>
    <p class="pl-3 bg-white w-full border border-gray-400 text-sm py-2.5">{{ $value }}</p>
</div>