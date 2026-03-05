@props([
    'route'         => null,
    'icon'          => null,
    'label',
    'isLeftMargin'  => false,
    'isRightMargin' => false,
    'openCloseKey'  => null,
])

<div class="navigation-btn-div">
    @if($route)
        <a
            @class([
                'rounded-md py-2 px-3 transition-colors duration-150',
                session('route_prefix') === $route ? 'bg-theme-main' : '',
                'ml-5' => $isLeftMargin,
                'mr-3' => $isRightMargin]
            )
            href="{{ route($route) }}">
            @if ($icon)
                <i class="{{ $icon }} navigation-btn-icon"></i>
            @endif
            <span class="text-sm pl-2">{{ $label }}</span>
        </a>
    @else
        <p class="navigation-open-close rounded-md py-2 px-3 mr-3 transition-colors duration-150" data-open-close-key="{{ $openCloseKey }}">
            @if($icon)
                <i class="{{ $icon }} navigation-btn-icon"></i>
            @endif
            <span class="text-sm pl-2">{{ $label }}</span>
            <i class="arrow la la-angle-down ml-2"></i>
        </p>
    @endif
</div>