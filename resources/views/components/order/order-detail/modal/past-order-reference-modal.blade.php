<!-- 過去注文引用モーダル -->
<div id="past_order_reference_modal" class="past_order_reference_modal_close hidden fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-md shadow-lg w-1/2">
        <div class="flex justify-between items-center px-4 py-2 border-b">
            <span class="font-semibold">過去注文から商品情報を引用</span>
        </div>
        <div class="p-4">
            <!-- 注文番号入力 -->
            <div class="flex gap-2 mb-4">
                <input type="text" id="past_order_no_input" class="border rounded-md px-2 py-1 w-full" placeholder="注文番号を入力" autocomplete="off">
                <button type="button" id="past_order_search_btn" class="btn bg-btn-enter text-white px-4 py-1 rounded-md whitespace-nowrap">検索</button>
            </div>
            <!-- 検索結果一覧 -->
            <div id="past_order_search_result" class="hidden">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-gray-200 text-left">
                            <th class="py-1 px-2 border">注文番号</th>
                            <th class="py-1 px-2 border">出荷日</th>
                            <th class="py-1 px-2 border">商品数</th>
                            <th class="py-1 px-2 border">選択</th>
                        </tr>
                    </thead>
                    <tbody id="past_order_search_result_tbody">
                    </tbody>
                </table>
            </div>
            <div id="past_order_search_empty" class="hidden text-gray-500 text-sm">該当する注文が見つかりませんでした。</div>
        </div>
    </div>
    <form method="POST" action="{{ route('past_order_item.reference') }}" id="past_order_item_reference_form">
        @csrf
        <input type="hidden" name="current_order_control_id" value="{{ $order->order_control_id }}">
        <input type="hidden" name="past_order_control_id" id="past_order_control_id_input">
    </form>
</div>