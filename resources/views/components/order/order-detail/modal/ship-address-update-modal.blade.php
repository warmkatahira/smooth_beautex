<div id="ship_address_update_modal" class="ship_address_update_modal_close hidden fixed z-50 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full">
    <div class="relative top-32 mx-auto shadow-lg rounded-md w-modal-window">
        <div class="flex justify-between items-center bg-theme-main text-black rounded-t-md px-4 py-2">
            <p>配送先住所を入力して下さい</p>
        </div>
        <div class="p-10 bg-theme-body">
            <form method="POST" action="{{ route('order_detail_update.ship_address') }}" id="ship_address_update_form">
                @csrf
                <x-form.input type="text" label="配送先国名コード" id="ship_country_code" name="ship_country_code" :value="$order->ship_country_code" />
                <x-form.input type="text" label="配送先都道府県コード" id="ship_province_code" name="ship_province_code" :value="$order->ship_province_code" />
                <x-form.input type="text" label="配送先都道府県名" id="ship_province_name" name="ship_province_name" :value="$order->ship_province_name" />
                <x-form.input type="text" label="配送先市区町村" id="ship_city" name="ship_city" :value="$order->ship_city" />
                <x-form.input type="text" label="配送先住所1" id="ship_address_1" name="ship_address_1" :value="$order->ship_address_1" />
                <x-form.input type="text" label="配送先住所2" id="ship_address_2" name="ship_address_2" :value="$order->ship_address_2" />
                <div class="flex justify-between mt-10">
                    <button type="button" id="ship_address_update_enter" class="btn bg-btn-enter p-3 text-white w-56"><i class="las la-check la-lg mr-1"></i>更新</button>
                    <button type="button" class="ship_address_update_modal_close btn bg-btn-cancel p-3 text-white w-56"><i class="las la-times la-lg mr-1"></i>キャンセル</button>
                </div>
                <input type="hidden" name="order_control_id" value="{{ $order->order_control_id }}">
            </form>
        </div>
    </div>
</div>