@props([
    'value',
    'label1',
    'label0',
])

@if($value)
    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold text-status-active-text bg-status-active-bg rounded-full">
        <span class="w-2 h-2 bg-status-active-dot rounded-full"></span>{{ $label1 }}
    </span>
@else
    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold text-status-inactive-text bg-status-inactive-bg rounded-full">
        <span class="w-2 h-2 bg-status-inactive-dot rounded-full"></span>{{ $label0 }}
    </span>
@endif