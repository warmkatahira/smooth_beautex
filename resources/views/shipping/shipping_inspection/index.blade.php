<x-app-layout>
    <div class="mt-5 flex flex-row items-start">
        <!-- スキャンで使用するinputタグ群 -->
        <div class="flex flex-col">
            <form method="POST" action="{{ route('shipping_inspection.complete') }}" class="m-0" id="shipping_inspection_form">
                @csrf
                <div class="flex flex-row mb-2">
                    <p class="pt-2.5 w-40 bg-black text-white pl-2">受注管理ID</p>
                    <input type="tel" id="order_control_id" name="order_control_id" class="" autocomplete="off">
                </div>
                <div class="flex flex-row mb-2">
                    <p class="pt-2.5 w-40 bg-black text-white pl-2">配送伝票番号</p>
                    <input type="tel" id="tracking_no" name="tracking_no" class="" autocomplete="off">
                </div>
                <div class="flex flex-row mb-2">
                    <p class="pt-2.5 w-40 bg-black text-white pl-2">商品識別コード</p>
                    <input type="tel" id="item_id_code" name="item_id_code" class="" autocomplete="off">
                </div>
            </form>
        </div>
        <!-- メッセージ -->
        <div class="flex flex-col w-10/12">
            <div class="ml-10 py-2 bg-black text-white text-center">メッセージ</div>
            <div id="message" class="ml-10 px-10 pt-5 h-24 bg-white border border-black"></div>
        </div>
        <!-- 残PCS -->
        <div class="flex flex-col w-2/12">
            <div class="ml-10 py-2 bg-black text-white text-center">残 PCS</div>
            <div id="remaining_pcs" class="text-5xl ml-10 px-10 pt-5 h-24 bg-white border border-black text-center"></div>
        </div>
    </div>
    <div class="disable_scrollbar flex flex-row overflow-scroll mt-2 gap-x-5">
        <x-shipping.shipping-inspection.table title="検品対象一覧" tableId="inspection_target_table" />
        <x-shipping.shipping-inspection.table title="検品完了一覧" tableId="inspection_complete_table" />
    </div>
</x-app-layout>
<x-item-id-code-alert title="出荷検品商品アラート" />
<x-shipping.shipping-inspection.lot-input-modal />
@vite(['resources/js/shipping/shipping_inspection/shipping_inspection.js'])