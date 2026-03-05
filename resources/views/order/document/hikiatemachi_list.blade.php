<x-document-layout>
    <div class="page-container">
        @php
            // 変数を初期化
            $order_count = 0;
        @endphp
        @foreach($orders as $order)
            @php
                // 最初のページに余計なページがでないように、改ページをコントロールするためのカウント
                // 受注をカウント
                $order_count++;
                // order_items を order_item_code でまとめて数量と未引当数を集計
                $aggregated_items = $order->order_items
                    ->groupBy('order_item_code')
                    ->map(function ($group) {
                        return [
                            'item' => $group->first()->item, // item 情報は同じなので first() で取得
                            'total_order_quantity' => $group->sum('order_quantity'),
                            'total_unallocated_quantity' => $group->sum('unallocated_quantity'),
                        ];
                    })
                    ->values(); // インデックスを連番に
            @endphp
            <div style="{{ $order_count != 1 ? 'page-break-before: always; padding-top: 0mm;' : '' }}">
                <div class="text-center">
                    <span class="text-2xl">引当待ちリスト</span>
                </div>
                <div class="mt-5 flex flex-col">
                    <div clas="flex flex-row gap-5">
                        <span>注文番号：</span>
                        <span>{{ $order->order_no }}</span>
                    </div>
                    <div clas="flex flex-row gap-5">
                        <span>注文日時：</span>
                        <span>{{ CarbonImmutable::parse($order->order_date . ' ' . $order->order_time)->isoFormat('Y/MM/DD HH:mm:ss') }}</span>
                    </div>
                    <div clas="flex flex-row gap-5">
                        <span>配送先名：</span>
                        <span>{{ $order->ship_name }}</span>
                    </div>
                </div>
            </div>
            <!-- 商品明細 -->
            <table class="mt-5 w-full">
                <thead>
                    <tr class="text-left bg-gray-200">
                        <th class="item_jan_code font-thin py-1 px-2 border border-black text-center">JANコード</th>
                        <th class="item_name font-thin py-1 px-2 border border-black text-center">商品名</th>
                        <th class="order_quantity font-thin py-1 px-2 border border-black text-center">数量</th>
                        <th class="unallocated_quantity font-thin py-1 px-2 border border-black text-center">未引当数</th>
                    </tr>
                </thead>
                <tbody class="">
                    @foreach($aggregated_items as $item)
                        <tr class="text-left cursor-default whitespace-nowrap">
                            <td class="item_jan_code py-1 px-2 border border-black text-center">{{ $item['item']->item_jan_code }}</td>
                            <td class="item_name py-1 px-2 border border-black">{{ $item['item']->item_name }}</td>
                            <td class="order_quantity py-1 px-2 border border-black text-right">{{ $item['total_order_quantity'] }}</td>
                            <td class="unallocated_quantity py-1 px-2 border border-black text-right">{{ $item['total_unallocated_quantity'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </div>
</x-document-layout>
@vite(['resources/sass/order/document/hikiatemachi_list.scss'])