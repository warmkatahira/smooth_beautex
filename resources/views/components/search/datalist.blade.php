<div class="flex flex-col">
    <label for="{{ $id }}" class="mb-1">{{ $label }}</label>
    <input type="text" list="{{ $listId }}" id="{{ $id }}" name="{{ $id }}" class="search_element py-2 rounded border-gray-400 text-xs" value="{{ session($id) }}" autocomplete="off">
    <datalist id="{{ $listId }}">
        @foreach($selectItems as $item)
            <option value="{{ $item[$optionValue] }}"></option>
        @endforeach
    </datalist>
</div>