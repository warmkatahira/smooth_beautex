<form method="GET" action="{{ route($route) }}" id="search_form">
    <p class="text-xs bg-black text-white py-1 text-center">検索条件</p>
    <div class="flex flex-col gap-y-2 p-3 bg-white min-w-60 text-xs border border-black">
        <div class="flex flex-col">
            <label for="search_operation_date_from" class="mb-1">操作日</label>
            <div class="flex flex-col">
                <input type="date" id="search_operation_date_from" name="search_operation_date_from" class="search_element date_from py-2 rounded border-gray-400 text-xs" value="{{ session('search_operation_date_from') }}" autocomplete="off">
                <span class="text-xs text-center">～</span>
                <input type="date" id="search_operation_date_to" name="search_operation_date_to" class="search_element date_to py-2 rounded border-gray-400 text-xs" value="{{ session('search_operation_date_to') }}" autocomplete="off">
            </div>
        </div>
        <input type="hidden" id="search_type" name="search_type" value="default">
        <div class="flex flex-row">
            <!-- 検索ボタン -->
            <button type="button" id="search_enter" class="btn bg-btn-enter p-3 text-white rounded w-5/12"><i class="las la-search la-lg mr-1"></i>検索</button>
            <!-- クリアボタン -->
            <button type="button" id="search_clear" class="btn bg-btn-cancel p-3 text-white rounded w-5/12 ml-auto"><i class="las la-eraser la-lg mr-1"></i>クリア</button>
        </div>
    </div>
</form>
