<div class="flex">
    <div id="dropdown" class="dropdown">
        <button id="dropdown_btn" class="dropdown_btn"><i class="las la-bars la-lg mr-1"></i>メニュー</button>
        <div class="dropdown-content" id="dropdown-content">
            <a href="{{ route('shipping_actual_download.download') }}" class="dropdown-content-element"><i class="las la-download la-lg mr-1"></i>出荷実績ダウンロード</a>
            <a href="{{ route('shipping_history_download.download') }}" class="dropdown-content-element"><i class="las la-download la-lg mr-1"></i>出荷履歴ダウンロード</a>
        </div>
    </div>
</div>