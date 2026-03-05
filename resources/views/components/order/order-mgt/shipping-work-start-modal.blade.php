<div id="shipping_work_start_modal" class="shipping_work_start_modal_close fixed hidden z-40 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full">
    <div class="relative top-32 mx-auto shadow-lg rounded-md w-modal-window">
        <div class="flex justify-between items-center bg-theme-main text-black rounded-t-md px-4 py-2">
            <p class="">出荷グループ情報を設定して下さい</p>
        </div>
        <div class="p-10 bg-theme-body">
            <div class="py-3">
                <label for="shipping_group_name" class="">出荷グループ名<span class="text-sm ml-5">※20文字以内</span></label>
                <input type="text" id="shipping_group_name" name="shipping_group_name" class="w-full text-sm px-5 border-none" autocomplete="off" form="operation_div_form">
            </div>
            <div class="py-3">
                <label for="estimated_shipping_date" class="">出荷予定日</label>
                <input type="date" id="estimated_shipping_date" name="estimated_shipping_date" class="w-full text-sm px-5 border-none" autocomplete="off" form="operation_div_form">
            </div>
            <input type="hidden" id="chk_count">
            <div class="flex justify-between mt-10">
                <button type="button" id="shipping_work_start_enter" class="btn bg-btn-enter p-3 text-white w-56"><i class="las la-check la-lg mr-1"></i>作業開始</button>
                <button type="button" class="shipping_work_start_modal_close btn bg-btn-cancel p-3 text-white w-56"><i class="las la-times la-lg mr-1"></i>キャンセル</button>
            </div>
        </div>
    </div>
</div>