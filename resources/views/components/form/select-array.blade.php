@props([
    'label',
    'id',
    'required' => null,
    'items',
    'optionValue',
    'optionText',
    'value',
    'name',
    'item',
])

<div class="flex flex-row">
    <label for="{{ $id }}" class="w-56 bg-black text-white py-2.5 pl-3 relative">
        {{ $label }}
        @if($required)
            <span class="absolute right-2 top-1/2 -translate-y-1/2 bg-white text-red-600 text-xs px-1.5 py-0.5 rounded">必須</span>
        @endif
    </label>
    <select id="{{ $id }}" name="{{ $name }}" class="w-96 text-sm">
        <option value=""></option>
        @foreach($items as $key => $item)
            <option value="{{ $key }}" @if(old($name, $value) == $key) selected @endif>{{ $item }}</option>
        @endforeach
    </select>
</div>