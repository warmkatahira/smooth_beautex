<div id="tracking_no_update_modal" class="tracking_no_update_modal_close hidden fixed z-50 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full">
    <div class="relative top-32 mx-auto shadow-lg rounded-md w-modal-window">
        <div class="flex justify-between items-center bg-theme-main text-black rounded-t-md px-4 py-2">
            <p>配送伝票番号を入力して下さい</p>
        </div>
        <div class="p-10 bg-theme-body">
            <form method="POST" action="{{ route('order_detail_update.tracking_no') }}" id="tracking_no_update_form">
                @csrf
                <x-form.input type="text" label="配送伝票番号" id="tracking_no" name="tracking_no" :value="$order->tracking_no" />
                <div class="flex justify-between mt-10">
                    <button type="button" id="tracking_no_update_enter" class="btn bg-btn-enter p-3 text-white w-56"><i class="las la-check la-lg mr-1"></i>更新</button>
                    <button type="button" class="tracking_no_update_modal_close btn bg-btn-cancel p-3 text-white w-56"><i class="las la-times la-lg mr-1"></i>キャンセル</button>
                </div>
                <input type="hidden" id="current_tracking_no" value="{{ $order->tracking_no }}">
                <input type="hidden" name="order_control_id" value="{{ $order->order_control_id }}">
            </form>
        </div>
    </div>
</div>