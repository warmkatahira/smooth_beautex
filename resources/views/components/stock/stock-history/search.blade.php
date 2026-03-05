<form method="GET" action="{{ route($route) }}" id="search_form">
    <p class="text-xs bg-black text-white py-1 text-center">検索条件</p>
    <div class="flex flex-col gap-y-2 p-3 bg-white min-w-60 text-xs border border-black">
        <x-search.date-period type="date" label="日付" fromId="search_stock_history_date_from" toId="search_stock_history_date_to" />
        <x-search.select label="区分" id="search_stock_history_category_id" :selectItems="$stockHistoryCategories" optionValue="stock_history_category_id" optionText="stock_history_category_name" />
        <x-search.input type="text" label="商品コード" id="search_item_code" />
        <x-search.input type="text" label="商品JANコード" id="search_item_jan_code" />
        <x-search.input type="text" label="商品名" id="search_item_name" />
        <x-search.input type="text" label="商品カテゴリ" id="search_item_category" />
        <input type="hidden" id="search_type" name="search_type" value="default">
        <div class="flex flex-row">
            <!-- 検索ボタン -->
            <button type="button" id="search_enter" class="btn bg-btn-enter p-3 text-white rounded w-5/12"><i class="las la-search la-lg mr-1"></i>検索</button>
            <!-- クリアボタン -->
            <button type="button" id="search_clear" class="btn bg-btn-cancel p-3 text-white rounded w-5/12 ml-auto"><i class="las la-eraser la-lg mr-1"></i>クリア</button>
        </div>
    </div>
</form>
