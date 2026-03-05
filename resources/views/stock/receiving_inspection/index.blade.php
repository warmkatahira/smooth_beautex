<x-app-layout>
    <div class="flex flex-row my-3 gap-20">
        <!-- 操作用ボタン -->
        <x-stock.receiving-inspection.operation-div />
    </div>
    <div class="flex flex-row gap-x-5">
        <div class="flex flex-col items-start w-72 gap-y-5">
            <div class="flex flex-col w-full">
                <p class="py-2 bg-black text-white text-center">商品識別コード</p>
                <input type="tel" id="item_id_code" name="item_id_code" class="h-24" autocomplete="off">
            </div>
            <div class="flex flex-col w-full">
                <div class="py-2 bg-black text-white text-center">合計 PCS</div>
                <div id="total_pcs" class="text-5xl pt-5 h-24 bg-white border border-black text-center"></div>
            </div>
            <div class="flex flex-col w-full">
                <div class="py-2 bg-black text-white text-center">入庫倉庫</div>
                <select id="base_id" name="base_id" class="text-sm" form="receiving_inspection_enter_form">
                    <option value=""></option>
                    @foreach($bases as $base)
                        <option value="{{ $base->base_id }}">{{ $base->base_name }}</value>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="disable_scrollbar flex flex-col overflow-scroll w-full">
            <div class="receiving_inspection_list bg-white overflow-x-auto overflow-y-auto border border-black">
                <table id="receiving_complete_table" class="text-sm w-full">
                    <thead>
                        <tr class="text-xs text-left text-white bg-black whitespace-nowrap sticky top-0">
                            <th class="font-thin py-1 px-2 text-center operation">操作</th>
                            <th class="font-thin py-1 px-2 text-center item_code">商品コード</th>
                            <th class="font-thin py-1 px-2 text-center item_jan_code">商品JANコード</th>
                            <th class="font-thin py-1 px-2 text-center item_name">商品名</th>
                            <th class="font-thin py-1 px-2 text-center quantity">数量</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
<x-item-id-code-alert title="入庫検品商品アラート" />
<x-stock.receiving-inspection.item-change-modal />
@vite(['resources/js/stock/receiving_inspection/receiving_inspection.js', 'resources/sass/stock/receiving_inspection/receiving_inspection.scss'])