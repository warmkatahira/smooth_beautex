<div class="flex flex-col">
    <label for="{{ $fromId }}" class="mb-1">{{ $label }}</label>
    <div class="flex flex-col">
        <input type="{{ $type }}" id="{{ $fromId }}" name="{{ $fromId }}" class="search_element date_from py-2 rounded border-gray-400 text-xs" value="{{ session($fromId) }}" autocomplete="off">
        <span class="text-xs text-center">～</span>
        <input type="{{ $type }}" id="{{ $toId }}" name="{{ $toId }}" class="search_element date_to py-2 rounded border-gray-400 text-xs" value="{{ session($toId) }}" autocomplete="off">
    </div>
</div>