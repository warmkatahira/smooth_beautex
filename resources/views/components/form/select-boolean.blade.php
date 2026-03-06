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
    <label for="{{ $id }}" class="text-gray-800 py-2.5 pl-3 relative">
        {{ $label }}
        @if($required)
            <span class="bg-pink-200 text-red-600 text-xs px-2 py-1 rounded">必須</span>
        @endif
    </label>
    <select id="{{ $id }}" name="{{ $name }}" class="w-full text-sm border border-gray-400">
        <option value=""></option>
        <option value="1" @if((string) old($name, $value) === '1') selected @endif>{{ $label1 }}</option>
        <option value="0" @if((string) old($name, $value) === '0') selected @endif>{{ $label0 }}</option>
    </select>
</div>