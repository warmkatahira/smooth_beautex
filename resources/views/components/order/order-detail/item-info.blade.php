<div>
    <p class="text-base font-semibold border-b pb-2 mb-4">商品情報</p>
    <div class="disable_scrollbar flex flex-grow overflow-scroll">
        <div class="order_detail_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
            <table class="text-xs">
                <thead>
                    <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                        <th class="font-thin py-1 px-2 text-center">商品画像</th>
                        <th class="font-thin py-1 px-2 text-center">商品引当</th>
                        <th class="font-thin py-1 px-2 text-center">在庫引当</th>
                        <th class="font-thin py-1 px-2 text-center">商品コード</th>
                        <th class="font-thin py-1 px-2 text-center">商品JANコード</th>
                        <th class="font-thin py-1 px-2 text-center">商品名</th>
                        <th class="font-thin py-1 px-2 text-center">出荷数</th>
                        <th class="font-thin py-1 px-2 text-center">未引当数</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($order->order_items as $order_item)
                        <tr class="text-left cursor-default whitespace-nowrap">
                            <td class="py-1 px-2 border">
                                <img src="{{ asset('storage/item_images/'.$order_item->item?->item_image_file_name) }}" class="w-10 h-10 mx-auto image_fade_in_modal_open">
                            </td>
                            <td class="py-1 px-2 border text-center">{!! displayCheckIfTrue($order_item->is_item_allocated) !!}</td>
                            <td class="py-1 px-2 border text-center">{!! displayCheckIfTrue($order_item->is_stock_allocated) !!}</td>
                            <td class="py-1 px-2 border">{{ $order_item->order_item_code }}</td>
                            <td class="py-1 px-2 border">{{ $order_item->item?->item_jan_code }}</td>
                            <td class="py-1 px-2 border">{{ $order_item->order_item_name }}</td>
                            <td class="py-1 px-2 border text-right">{{ number_format($order_item->order_quantity) }}</td>
                            <td class="py-1 px-2 border text-right">{{ number_format($order_item->unallocated_quantity) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>