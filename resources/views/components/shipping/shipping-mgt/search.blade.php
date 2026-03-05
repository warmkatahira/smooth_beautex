<form method="GET" action="{{ route($route) }}" id="search_form">
    <p class="text-xs bg-black text-white py-1 text-center">検索条件</p>
    <div class="flex flex-col gap-y-2 p-3 bg-white min-w-60 text-xs border border-black">
        <x-search.input type="text" label="注文番号" id="search_order_no" />
        <x-search.input type="text" label="受注管理ID" id="search_order_control_id" />
        <x-search.select label="受注区分" id="search_order_category_id" :selectItems="$orderCategories" optionValue="order_category_id" optionText="order_category_name" />
        <x-search.select label="出荷倉庫" id="search_shipping_base_id" :selectItems="$bases" optionValue="base_id" optionText="base_name" />
        <x-search.input type="text" label="配送先名" id="search_ship_name" />
        <x-search.select label="配送先都道府県" id="search_ship_prefecture_name" :selectItems="$prefectures" optionValue="prefecture_name" optionText="prefecture_name" />
        <x-search.select-delivery-company :deliveryCompanies="$deliveryCompanies" />
        <x-search.select-boolean label="出荷検品状態" id="search_is_shipping_inspection_complete" label0="未完了" label1="完了" />
        <input type="hidden" id="search_type" name="search_type" value="default">
        <input type="hidden" name="search_shipping_group_id" value="{{ session('search_shipping_group_id') }}">
        <div class="flex flex-row">
            <!-- 検索ボタン -->
            <button type="button" id="search_enter" class="btn bg-btn-enter p-3 text-white rounded w-5/12"><i class="las la-search la-lg mr-1"></i>検索</button>
            <!-- クリアボタン -->
            <button type="button" id="search_clear" class="btn bg-btn-cancel p-3 text-white rounded w-5/12 ml-auto"><i class="las la-eraser la-lg mr-1"></i>クリア</button>
        </div>
    </div>
</form>
