@props([
    'type',
    'id',
    'name',
    'placeholder' => null,
])

<th class="px-3">
    <div class="flex flex-row items-center min-w-0">
        <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" class="search_element rounded border-gray-400 text-xs font-thin mx-2 py-1 min-w-28 w-full" value="{{ session($name) }}" placeholder="{{ $placeholder }}" autocomplete="off">
        <button type="button" class="filter_clear btn hidden flex-shrink-0" data-target="{{ $name }}"><i class="las la-times la-lg text-red-500"></i></button>
    </div>
</th>