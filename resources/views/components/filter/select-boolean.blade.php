@props([
    'id',
    'name',
    'label1',
    'label0',
])

<th class="px-3">
    <div class="flex flex-row items-center">
        <select id="{{ $id }}" name="{{ $name }}" class="search_element rounded border-gray-400 text-xs font-thin mx-2 py-1">
            <option value="" @if(is_null(session($name))) selected @endif></option>
            <option value="1" @if(session($name) === '1') selected @endif>{{ $label1 }}</option>
            <option value="0" @if(session($name) === '0') selected @endif>{{ $label0 }}</option>
        </select>
        <button type="button" class="filter_clear btn hidden" data-target="{{ $name }}"><i class="las la-times la-lg text-red-500 pr-2"></i></button>
    </div>
</th>