<div id="order_item_create_modal" class="order_item_create_modal_close hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
    <div class="bg-white rounded-lg w-2/3 p-6">
        <div class="flex justify-between items-center mb-3">
            <p class="font-semibold text-base">商品追加</p>
        </div>
        <!-- 検索欄 -->
        <input id="item_search_input" type="text" placeholder="商品コード・商品名・JANコードで検索" class="border w-full p-2 mb-3 text-sm" autocomplete="off">
        <!-- 検索結果一覧 -->
        <div id="item_search_results" class="overflow-y-auto max-h-64 border text-xs mb-4">
            <p class="p-2 text-gray-400">キーワードを入力してください</p>
        </div>
        <!-- 選択後の入力エリア -->
        <form method="POST" action="{{ route('order_item_create.create') }}" id="order_item_create_form">
            @csrf
            <div id="item_input_area" class="hidden border-t pt-4">
                <input type="hidden" id="order_item_code" name="order_item_code">
                <input type="hidden" id="order_control_id" name="order_control_id" value="{{ $order->order_control_id }}">
                <p id="selected_item_label" class="font-semibold text-sm mb-3"></p>
                <div class="flex gap-4">
                    <div>
                        <label class="text-xs text-gray-500">数量</label>
                        <input type="number" id="shipping_quantity" name="shipping_quantity" min="1" class="border p-2 w-28 block">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">単価</label>
                        <input type="number" id="order_item_unit_price" name="order_item_unit_price" min="0" class="border p-2 w-28 block">
                    </div>
                </div>
                <button type="button" id="order_item_create_enter" class="btn bg-btn-enter text-white mt-4 px-5 py-1 rounded-md">追加</button>
            </div>
        </form>
    </div>
</div>