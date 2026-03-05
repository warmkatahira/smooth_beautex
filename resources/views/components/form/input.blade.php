@props([
    'label',
    'id',
    'required' => null,
    'type',
    'value',
    'name',
    'tippy' => null,
])

<div class="flex flex-row">
    <label for="{{ $id }}" class="w-56 bg-black text-white py-2.5 pl-3 relative">
        {{ $label }}
        @if(!is_null($tippy))
            <i class="{{ $tippy }} las la-info-circle la-lg ml-1 pt-0.5"></i>
        @endif
        @if($required)
            <span class="absolute right-2 top-1/2 -translate-y-1/2 bg-white text-red-600 text-xs px-1.5 py-0.5 rounded">必須</span>
        @endif
    </label>
    <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" class="pl-3 bg-white w-96 text-sm h-10 border border-black" value="{{ old($name, $value) }}" autocomplete="off">
</div>