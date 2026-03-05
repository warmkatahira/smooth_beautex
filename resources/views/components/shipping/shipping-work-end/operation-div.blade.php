<div class="flex">
    <div id="dropdown" class="dropdown">
        <button id="dropdown_btn" class="dropdown_btn"><i class="las la-bars la-lg mr-1"></i>メニュー</button>
        <div class="dropdown-content" id="dropdown-content">
            <form method="POST" action="{{ route('shipping_work_end.enter') }}" id="shipping_work_end_form" class="m-0">
                @csrf
                <button type="button" id="shipping_work_end_enter" class="dropdown-content-element"><i class="las la-truck mr-1 la-lg"></i>出荷完了</button>
            </form>
        </div>
    </div>
</div>