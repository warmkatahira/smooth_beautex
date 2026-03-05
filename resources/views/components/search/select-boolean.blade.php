<div class="flex flex-col">
    <label for="{{ $id }}" class="mb-1">{{ $label }}</label>
    <select id="{{ $id }}" name="{{ $id }}" class="search_element rounded border-gray-400 text-xs">
        <option value="" @if(is_null(session($id))) selected @endif></option>
        <option value="1" @if(session($id) === '1') selected @endif>{{ $label1 }}</value>
        <option value="0" @if(session($id) === '0') selected @endif>{{ $label0 }}</value>
    </select>
</div>