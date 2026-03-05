<x-app-layout>
    <div class="flex flex-row gap-10 mt-3">
        <x-dashboard.info-div label="出荷作業前件数" orderCountId="sagyo_mae_order_count" shipQuantityId="sagyo_mae_ship_quantity" />
        <x-dashboard.info-div label="出荷作業中件数" orderCountId="sagyo_chu_order_count" shipQuantityId="sagyo_chu_ship_quantity" />
        <x-dashboard.info-div labelId="total_shipped_label" orderCountId="total_shipped_count" shipQuantityId="total_shipped_quantity" />
    </div>
    <div class="flex flex-row items-center gap-5 mt-5">
        <p id="calendar_year" class="text-center text-lg"></p>
        <div class="flex flex-row gap-5">
            <button type="button" id="previous_month" class="btn month_change tippy_previous_month" data-month="{{ $previous_month }}"><i class="las la-caret-square-left la-2x"></i></button>
            <button type="button" id="current_month" class="btn month_change tippy_current_month" data-month="{{ $current_month }}"><i class="las la-calendar la-2x"></i></button>
            <button type="button" id="next_month" class="btn month_change tippy_next_month" data-month="{{ $next_month }}"><i class="las la-caret-square-right la-2x"></i></button>
        </div>
    </div>
    <div class="flex flex-row items-start gap-5 mt-2">
        <x-dashboard.shipping-calendar />
        <x-dashboard.shipping-history-chart />
    </div>
</x-app-layout>
@vite(['resources/js/dashboard/dashboard.js', 'resources/sass/dashboard/dashboard.scss'])