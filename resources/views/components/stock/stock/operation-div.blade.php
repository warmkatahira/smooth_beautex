<div class="flex">
    <div id="dropdown" class="dropdown">
        <button id="dropdown_btn" class="dropdown_btn"><i class="las la-bars la-lg mr-1"></i>メニュー</button>
        <div class="dropdown-content" id="dropdown-content">
            <a href="{{ route('stock_download.download', ['route_name' => Route::currentRouteName()]) }}" class="dropdown-content-element"><i class="las la-download la-lg mr-1"></i>ダウンロード</a>
            @can('warm_check')
                <form method="POST" action="{{ route('item_location_update.update') }}" id="item_location_update_form" enctype="multipart/form-data" class="m-0">
                    @csrf
                    <div class="flex select_file dropdown-content-element">
                        <label class="text-xs cursor-pointer">
                            <i class="las la-upload la-lg mr-1"></i>商品ロケーション更新
                            <input type="file" name="select_file" class="hidden">
                        </label>
                    </div>
                </form>
            @endcan
        </div>
    </div>
</div>