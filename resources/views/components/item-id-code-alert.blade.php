<div id="item_id_code_alert_modal" class="item_id_code_alert_modal_close hidden fixed z-40 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-40">
    <div class="relative top-20 mx-auto shadow-lg bg-white">
        <p class="text-xl text-red-500 text-center bg-theme-sub py-5"><i class="las la-exclamation-triangle la-lg mr-1"></i>{{ $title }}<i class="las la-exclamation-triangle la-lg ml-1"></i></p>
        <div class="mt-5">
            <div class="flex flex-col pb-5">
                <p id="error_message" class="mt-10 text-center text-2xl"></p>
            </div>
            <input type="tel" id="item_id_code_alert_modal_focus_element" class="w-0 h-0 border-transparent focus:border-transparent focus:ring-0" autocomplete="off">
        </div>
        <div class="flex pb-5 pr-5">
            <button type="button" class="item_id_code_alert_modal_close mt-5 ml-auto w-40 px-5 py-2 bg-black text-white">閉じる</button>
        </div>
    </div>
</div>