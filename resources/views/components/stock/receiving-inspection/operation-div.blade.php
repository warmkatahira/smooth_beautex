<div class="flex">
    <div id="dropdown" class="dropdown">
        <button id="dropdown_btn" class="dropdown_btn"><i class="las la-bars la-lg mr-1"></i>メニュー</button>
        <div class="dropdown-content" id="dropdown-content">
            <form method="POST" action="{{ route('receiving_inspection_enter.enter') }}" id="receiving_inspection_enter_form" class="m-0">
                @csrf
                <input type="hidden" id="comment" name="comment" value="">
                <button type="button" id="receiving_inspection_enter" class="dropdown-content-element"><i class="las la-clipboard-check mr-1  la-lg"></i>入庫検品確定</button>
            </form>
        </div>
    </div>
</div>