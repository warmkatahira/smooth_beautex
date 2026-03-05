<x-document-layout>
    <div class="page-container">
        <div class="flex flex-row">
            <div class="flex flex-col">
                <span class="text-xl">トータルピッキングリスト</span>
                <span>{{ SystemEnum::CUSTOMER_NAME }}出荷システム</span>
            </div>
            <div class="flex text-center text-2xl ml-auto mr-1">
                <p class="bg-theme-sub border border-black py-2 px-5">合計数</p>
                <p class="py-2 px-10 border-y border-r border-black">{{ number_format($report_total_order_quantity) }}</p>
            </div>
        </div>
        <table class="w-full mt-5">
            <thead>
                <tr class="bg-theme-sub">
                    <th class="item_location text-left py-2 font-thin pl-2">ロケ</th>
                    <th class="item_jan_code text-left py-2 font-thin">商品JANコード</th>
                    <th class="item_name text-left py-2 font-thin">商品名</th>
                    <th class="order_quantity text-right py-2 font-thin pr-2">数量</th>
                    <th class="remaining_stock text-right py-2 font-thin pr-2">残数</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 0; ?>
                @foreach($items as $item)
                    <?php $count++; ?>
                    <!-- 偶数行だけ背景色を塗る -->
                    <tr class="@if($count % 2 == 0) bg-gray-200 @endif border-y border-black">
                        <td class="item_location text-left py-2 pl-2">{{ $item->item_location }}</td>
                        <td class="item_jan_code text-left py-2">{{ $item->item_jan_code }}</td>
                        <td class="item_name text-left py-2">{{ $item->item_name }}</td>
                        <td class="order_quantity text-right py-2 pr-2 text-2xl">{{ number_format($item->total_order_quantity) }}</td>
                        <td class="remaining_stock text-right py-2 pr-2 text-xl">{{ number_format($item->remaining_stock) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-document-layout>
@vite(['resources/sass/shipping/document/total_picking_list.scss'])