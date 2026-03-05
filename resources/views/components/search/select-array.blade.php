@props([
    'label',
    'id',
    'required' => null,
    'items',
    'optionValue',
    'optionText',
    'value',
    'item',
])

<div class="flex flex-col">
    <label for="{{ $id }}" class="mb-1">{{ $label }}</label>
    <select id="{{ $id }}" name="{{ $id }}" class="search_element rounded border-gray-400 text-xs">
        <option value=""></option>
        @foreach($items as $key => $item)
            <option value="{{ $key }}" @if(session($id) === $key) selected @endif>{{ $item }}</option>
        @endforeach
    </select>
</div>