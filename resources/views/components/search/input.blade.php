<div class="flex flex-col">
    <label for="{{ $id }}" class="mb-1">{{ $label }}</label>
    <input type="{{ $type }}" id="{{ $id }}" name="{{ $id }}" class="search_element py-2 rounded border-gray-400 text-xs" value="{{ session($id) }}" autocomplete="off">
</div>