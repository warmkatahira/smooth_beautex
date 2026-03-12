@props([
    'label',
    'id',
    'required' => null,
    'name',
    'label0',
    'label1',
    'value',
])

<div class="flex flex-col bg-white py-2 px-3 w-form-div">
    <label class="text-gray-800 py-2.5 pl-3 relative">
        {{ $label }}
        @if($required)
            <span class="bg-pink-200 text-red-600 text-xs px-2 py-1 rounded">必須</span>
        @endif
    </label>
    <label class="relative inline-flex items-center cursor-pointer ml-3 gap-3">
        <input type="checkbox" id="{{ $id }}" name="{{ $name }}" value="1" class="sr-only peer" @checked(old($name, $value))>
        <div class="w-16 h-6 bg-gray-300 rounded-full peer-checked:bg-green-500 transition-colors duration-200"></div>
        <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform duration-200 peer-checked:translate-x-10"></div>
        <span class="text-sm text-gray-600 peer-checked:hidden">{{ $label0 }}</span>
        <span class="hidden text-sm text-green-600 peer-checked:inline">{{ $label1 }}</span>
    </label>
</div>