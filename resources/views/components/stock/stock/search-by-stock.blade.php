<form method="GET" action="{{ route($route) }}" id="search_form">
    <p class="text-xs bg-black text-white py-1 text-center">検索条件</p>
    <div class="flex flex-col gap-y-2 p-3 bg-white min-w-60 text-xs border border-black">
        <x-search.select label="倉庫名" id="search_base_id" :selectItems="$bases" optionValue="base_id" optionText="base_name" />
        <x-search.input type="text" label="商品コード" id="search_item_code" />
        <x-search.input type="text" label="商品JANコード" id="search_item_jan_code" />
        <x-search.input type="text" label="商品名" id="search_item_name" />
        <x-search.input type="text" label="商品カテゴリ" id="search_item_category" />
        <x-search.select-boolean label="有効在庫数状態" id="search_available_stock_status" label0="在庫なし" label1="在庫あり" />
        <x-search.select-boolean label="在庫管理" id="search_is_stock_managed" label0="無効" label1="有効" />
        <input type="hidden" id="search_type" name="search_type" value="default">
        <div class="flex flex-row">
            <!-- 検索ボタン -->
            <button type="button" id="search_enter" class="btn bg-btn-enter p-3 text-white rounded w-5/12"><i class="las la-search la-lg mr-1"></i>検索</button>
            <!-- クリアボタン -->
            <button type="button" id="search_clear" class="btn bg-btn-cancel p-3 text-white rounded w-5/12 ml-auto"><i class="las la-eraser la-lg mr-1"></i>クリア</button>
        </div>
    </div>
</form>
