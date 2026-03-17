<div class="flex">
    <div id="dropdown" class="dropdown">
        <button id="dropdown_btn" class="dropdown_btn"><i class="las la-bars la-lg mr-1"></i>メニュー</button>
        <div class="dropdown-content" id="dropdown-content">
            <form method="GET" action="{{ route('order_mgt.allocate') }}" id="allocate_form" class="m-0">
                <button type="button" id="allocate_enter" class="dropdown-content-element"><i class="las la-sync-alt la-lg mr-1"></i>引当処理</button>
            </form>
            <button type="button" id="order_delete" class="dropdown-content-element"><i class="las la-trash-alt la-lg mr-1"></i>受注削除</button>
            @if(session('filter_order_status_id') == OrderStatusEnum::SHUKKA_MACHI)
                <button type="button" id="shipping_work_start_modal_open" class="dropdown-content-element"><i class="las la-flag-checkered la-lg mr-1"></i>出荷作業開始</button>
            @endif
        </div>
    </div>
</div>
<form method="POST" action="" id="operation_div_form" class="m-0">
    @csrf
</form>