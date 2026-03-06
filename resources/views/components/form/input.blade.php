@props([
    'label',
    'id',
    'name',
    'required' => null,
    'type',
    'value',
    'tippy' => null,
])

<div class="flex flex-col bg-white py-2 px-3 w-form-div">
    <label for="{{ $id }}" class="text-gray-800 py-2.5 pl-3">
        {{ $label }}
        @if(!is_null($tippy))
            <i class="{{ $tippy }} las la-info-circle la-lg ml-1 pt-0.5"></i>
        @endif
        @if($required)
            <span class="bg-pink-200 text-red-600 text-xs px-2 py-1 rounded">必須</span>
        @endif
    </label>
    <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" class="pl-3 bg-white w-full text-sm border border-gray-400" value="{{ old($name, $value) }}" autocomplete="off">
</div>