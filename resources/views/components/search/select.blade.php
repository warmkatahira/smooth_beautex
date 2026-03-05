<div class="flex flex-col">
    <label for="{{ $id }}" class="mb-1">{{ $label }}</label>
    <select id="{{ $id }}" name="{{ $id }}" class="search_element rounded border-gray-400 text-xs">
        <option value=""></option>
        @foreach($selectItems as $item)
            <option value="{{ $item->$optionValue }}" @if(session($id) == $item->$optionValue) selected @endif>{{ $item->$optionText }}</value>
        @endforeach
    </select>
</div>