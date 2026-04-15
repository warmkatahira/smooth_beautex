<th class="px-3">
    <div class="flex flex-row items-center gap-1 min-w-0">
        <div class="flex flex-row items-center min-w-0">
            <input type="tel" id="filter_total_stock_min" name="filter_total_stock_min" class="search_element rounded border border-gray-400 text-xs font-thin py-1 w-16" value="{{ session('filter_total_stock_min') }}" placeholder="以上" autocomplete="off">
            <button type="button" class="filter_clear btn hidden flex-shrink-0" data-target="filter_total_stock_min"><i class="las la-times la-lg text-red-500"></i></button>
        </div>
        <span class="text-xs flex-shrink-0">〜</span>
        <div class="flex flex-row items-center min-w-0">
            <input type="tel" id="filter_total_stock_max" name="filter_total_stock_max" class="search_element rounded border border-gray-400 text-xs font-thin py-1 w-16" value="{{ session('filter_total_stock_max') }}" placeholder="以下" autocomplete="off">
            <button type="button" class="filter_clear btn hidden flex-shrink-0" data-target="filter_total_stock_max"><i class="las la-times la-lg text-red-500"></i></button>
        </div>
    </div>
</th>