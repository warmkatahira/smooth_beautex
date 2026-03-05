<div id="desired_delivery_date_update_modal" class="desired_delivery_date_update_modal_close hidden fixed z-50 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full">
    <div class="relative top-32 mx-auto shadow-lg rounded-md w-modal-window">
        <div class="flex justify-between items-center bg-theme-main text-black rounded-t-md px-4 py-2">
            <p>配送希望日を入力して下さい</p>
        </div>
        <div class="p-10 bg-theme-body">
            <form method="POST" action="{{ route('order_detail_update.desired_delivery_date') }}" id="desired_delivery_date_update_form">
                @csrf
                <x-form.input type="date" label="配送希望日" id="desired_delivery_date" name="desired_delivery_date" :value="$order->desired_delivery_date" />
                <div class="flex justify-between mt-10">
                    <button type="button" id="desired_delivery_date_update_enter" class="btn bg-btn-enter p-3 text-white w-56"><i class="las la-check la-lg mr-1"></i>更新</button>
                    <button type="button" class="desired_delivery_date_update_modal_close btn bg-btn-cancel p-3 text-white w-56"><i class="las la-times la-lg mr-1"></i>キャンセル</button>
                </div>
                <input type="hidden" id="current_desired_delivery_date" value="{{ $order->desired_delivery_date }}">
                <input type="hidden" name="order_control_id" value="{{ $order->order_control_id }}">
            </form>
        </div>
    </div>
</div>