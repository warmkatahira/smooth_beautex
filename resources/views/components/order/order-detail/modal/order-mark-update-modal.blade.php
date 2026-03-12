<div id="order_mark_update_modal" class="order_mark_update_modal_close hidden fixed z-50 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full">
    <div class="relative top-32 mx-auto shadow-lg rounded-md w-modal-window">
        <div class="flex justify-between items-center bg-theme-main text-black rounded-t-md px-4 py-2">
            <p>受注マークを入力して下さい</p>
        </div>
        <div class="p-10 bg-theme-body">
            <form method="POST" action="{{ route('order_detail_update.order_mark') }}" id="order_mark_update_form">
                @csrf
                <x-form.input type="text" label="受注マーク" id="order_mark" name="order_mark" :value="$order->order_mark" />
                <div class="flex justify-between mt-10">
                    <button type="button" id="order_mark_update_enter" class="btn bg-btn-enter p-3 text-white w-56"><i class="las la-check la-lg mr-1"></i>更新</button>
                    <button type="button" class="order_mark_update_modal_close btn bg-btn-cancel p-3 text-white w-56"><i class="las la-times la-lg mr-1"></i>キャンセル</button>
                </div>
                <input type="hidden" id="current_order_mark" value="{{ $order->order_mark }}">
                <input type="hidden" name="order_control_id" value="{{ $order->order_control_id }}">
            </form>
        </div>
    </div>
</div>