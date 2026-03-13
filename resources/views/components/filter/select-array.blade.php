@props([
    'id',
    'name',
    'items',
])

<th class="px-3">
    <div class="flex flex-row items-center">
        <select id="{{ $id }}" name="{{ $name }}" class="search_element rounded border-gray-400 text-xs font-thin mx-2 py-1">
            <option value=""></option>
            @foreach($items as $key => $value)
                <option value="{{ $key }}" @if(session($name) === $key) selected @endif>{{ $value }}</option>
            @endforeach
        </select>
        <button type="button" class="filter_clear btn hidden" data-target="{{ $name }}"><i class="las la-times la-lg text-red-500 pr-2"></i></button>
    </div>
</th>