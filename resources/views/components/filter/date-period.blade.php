@props([
    'type',
    'fromId',
    'fromName',
    'toId',
    'toName',
    'resetToday' => false,
])

<th class="px-3">
    <div class="flex items-center gap-1 min-w-0">
        <div class="date_range_wrap flex items-center gap-1 border border-gray-400 rounded px-2 mx-2">
            <input type="date" id="{{ $fromId }}" name="{{ $fromName }}" class="search_element date_from font-thin py-1 border-none outline-none text-xs bg-transparent w-28" value="{{ session($fromName) }}" autocomplete="off"/>
            <span class="text-xs text-gray-400">〜</span>
            <input type="date" id="{{ $toId }}" name="{{ $toName }}" class="search_element date_to font-thin py-1 border-none outline-none text-xs bg-transparent w-28" value="{{ session($toName) }}" autocomplete="off"/>
        </div>
        <button type="button" class="filter_clear btn hidden flex-shrink-0" data-target-from="{{ $fromId }}" data-target-to="{{ $toId }}" data-reset-today="{{ $resetToday }}"><i class="las la-times la-lg text-red-500"></i></button>
    </div>
</th>