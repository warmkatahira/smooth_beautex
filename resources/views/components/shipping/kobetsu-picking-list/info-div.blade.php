@props([
    'label',
    'value',
    'parentDivWidth' => 'w-1/2',
    'childSpanLabelWidth' => 'w-1/3',
    'childSpanValueWidth' => 'w-2/3',
    'isPrice' => false,
])

<div class="text-xs flex border-b border-black {{ $parentDivWidth }}">
    <span class="{{ $childSpanLabelWidth }} pl-1 py-1 bg-theme-sub">{{ $label }}</span>
    <span class="{{ $childSpanValueWidth }} pl-1 py-1 bg-gray-100">{!! $isPrice ? number_format($value).' 円' : nl2br(e($value)) !!}</span>
</div>