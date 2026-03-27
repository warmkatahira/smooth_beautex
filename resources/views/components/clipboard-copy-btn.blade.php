@props([
    'value',
    'label',
])

@if(!is_null($value))
    <i class="las la-copy la-lg pl-1 clipboard_copy tippy_clipboard_copy cursor-pointer opacity-0 group-hover/clipboard:opacity-100 transition-opacity duration-200" data-clipboard-value="{{ $value }}" data-clipboard-label="{{ $label }}"></i>
@endif