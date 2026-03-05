<div class="flex">
    <div id="dropdown" class="dropdown">
        <button id="dropdown_btn" class="dropdown_btn"><i class="las la-bars la-lg mr-1"></i>メニュー</button>
        <div class="dropdown-content" id="dropdown-content">
            <form method="GET" action="{{ route('order_mgt.allocate') }}" id="allocate_form" class="m-0">
                <button type="button" id="allocate_enter" class="dropdown-content-element"><i class="las la-sync-alt la-lg mr-1"></i>引当処理</button>
            </form>
            <button type="button" id="order_delete" class="dropdown-content-element"><i class="las la-trash-alt la-lg mr-1"></i>受注削除</button>
            @if(session('search_order_status_id') == OrderStatusEnum::KAKUNIN_MACHI)
                <a href="{{ route('kakuninmachi_list.create') }}" class="dropdown-content-element" target="_blank"><i class="las la-clipboard-list mr-1 la-lg"></i>確認待ちリスト</a>
            @endif
            @if(session('search_order_status_id') == OrderStatusEnum::HIKIATE_MACHI)
                <a href="{{ route('hikiatemachi_list.create') }}" class="dropdown-content-element" target="_blank"><i class="las la-clipboard-list mr-1 la-lg"></i>引当待ちリスト</a>
            @endif
            @if(session('search_order_status_id') == OrderStatusEnum::SHUKKA_MACHI)
                <button type="button" id="shipping_work_start_modal_open" class="dropdown-content-element"><i class="las la-flag-checkered la-lg mr-1"></i>出荷作業開始</button>
            @endif
        </div>
    </div>
</div>
<form method="POST" action="" id="operation_div_form" class="m-0">
    @csrf
</form>