@props([
    'listId',
    'id',
    'name',
    'selectItems',
    'optionValue',
])

<th class="px-3">
    <div class="flex flex-row items-center">
        <input type="text" list="{{ $listId }}" id="{{ $id }}" name="{{ $name }}" class="search_element rounded border-gray-400 text-xs font-thin mx-2 py-1 min-w-28 w-full" value="{{ session($name) }}" autocomplete="off">
        <datalist id="{{ $listId }}">
            @foreach($selectItems as $item)
                <option value="{{ $item[$optionValue] }}"></option>
            @endforeach
        </datalist>
        <button type="button" class="filter_clear btn hidden" data-target="{{ $name }}"><i class="las la-times la-lg text-red-500"></i></button>
    </div>
</th>