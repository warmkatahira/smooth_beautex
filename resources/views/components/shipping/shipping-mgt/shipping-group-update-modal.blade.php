<div id="shipping_group_update_modal" class="shipping_group_update_modal_close hidden fixed z-50 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full">
    <div class="relative top-32 mx-auto shadow-lg rounded-md w-modal-window">
        <div class="flex justify-between items-center bg-theme-main text-black rounded-t-md px-4 py-2">
            <p>出荷グループで更新する内容を入力して下さい</p>
        </div>
        <div class="p-10 bg-theme-body">
            <form method="POST" action="{{ route('shipping_group_update.update') }}" id="shipping_group_update_form">
                @csrf
                <div class="py-3">
                    <label for="shipping_group_name" class="">出荷グループ名<span class="text-sm ml-5">※20文字以内</span></label>
                    <input type="text" id="shipping_group_name" name="shipping_group_name" class="w-full text-sm px-5 border-none" autocomplete="off" value="{{ $shippingGroup->shipping_group_name }}">
                </div>
                <div class="py-3">
                    <label for="estimated_shipping_date" class="">出荷予定日</label>
                    <input type="date" id="estimated_shipping_date" name="estimated_shipping_date" class="w-full text-sm px-5 border-none" autocomplete="off" value="{{ $shippingGroup->estimated_shipping_date }}">
                </div>
                <div class="flex justify-between mt-10">
                    <button type="button" id="shipping_group_update_enter" class="btn bg-btn-enter p-3 text-white w-56"><i class="las la-check la-lg mr-1"></i>更新</button>
                    <button type="button" class="shipping_group_update_modal_close btn bg-btn-cancel p-3 text-white w-56"><i class="las la-times la-lg mr-1"></i>キャンセル</button>
                </div>
                <input type="hidden" id="current_shipping_group_name" value="{{ $shippingGroup->shipping_group_name }}">
                <input type="hidden" id="current_estimated_shipping_date" value="{{ $shippingGroup->estimated_shipping_date }}">
                <input type="hidden" name="shipping_group_id" value="{{ $shippingGroup->shipping_group_id }}">
            </form>
        </div>
    </div>
</div>