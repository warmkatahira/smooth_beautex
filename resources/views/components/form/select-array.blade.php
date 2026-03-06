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
    'tippy' => null,
])

<div class="flex flex-col bg-white py-2 px-3 w-form-div">
    <label for="{{ $id }}" class="w-56 text-gray-800 py-2.5 pl-3">
        {{ $label }}
        @if(!is_null($tippy))
            <i class="{{ $tippy }} las la-info-circle la-lg ml-1 pt-0.5"></i>
        @endif
        @if($required)
            <span class="bg-pink-200 text-red-600 text-xs px-2 py-1 rounded">必須</span>
        @endif
    </label>
    <select id="{{ $id }}" name="{{ $name }}" class="w-full text-sm border border-gray-400">
        <option value=""></option>
        @foreach($items as $key => $item)
            <option value="{{ $key }}" @if((string) old($name, $value) === (string) $key) selected @endif>{{ $item }}</option>
        @endforeach
    </select>
</div>