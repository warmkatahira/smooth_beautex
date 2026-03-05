<div class="flex">
    <div id="dropdown" class="dropdown">
        <button id="dropdown_btn" class="dropdown_btn"><i class="las la-bars la-lg mr-1"></i>メニュー</button>
        <div class="dropdown-content" id="dropdown-content">
            <form method="POST" action="{{ route('order_import.import') }}" id="order_import_form" enctype="multipart/form-data" class="m-0">
                @csrf
                <div class="flex select_file dropdown-content-element">
                    <label class="text-xs cursor-pointer">
                        <i class="las la-upload la-lg mr-1"></i>取込
                        <input type="file" name="select_file" class="hidden">
                    </label>
                </div>
            </form>
        </div>
    </div>
</div>