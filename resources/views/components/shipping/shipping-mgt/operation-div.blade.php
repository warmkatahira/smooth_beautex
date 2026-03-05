<div class="flex">
    <div id="dropdown" class="dropdown">
        <button id="dropdown_btn" class="dropdown_btn"><i class="las la-bars la-lg mr-1"></i>メニュー</button>
        <div class="dropdown-content" id="dropdown-content">
            <form method="POST" action="{{ route('tracking_no_import.import') }}" id="tracking_no_import_form" enctype="multipart/form-data" class="m-0">
                @csrf
                <div class="flex select_file dropdown-content-element">
                    <label class="text-xs cursor-pointer">
                        <i class="las la-upload la-lg mr-1"></i>配送伝票番号取込
                        <input type="file" name="select_file" class="hidden">
                    </label>
                </div>
            </form>
            <button type="button" id="return_to_shukka_machi" class="dropdown-content-element"><i class="las la-undo la-lg mr-1"></i>出荷待ちへ戻す</button>
        </div>
    </div>
</div>
<form method="POST" action="" id="operation_div_form" class="m-0">
    @csrf
</form>