@php
    $has_lots = $order->order_items->contains(fn($item) => $item->order_item_lots->isNotEmpty());
@endphp

<div>
    <p class="text-base font-semibold border-b pb-2 mb-4">商品情報</p>
    <div class="disable_scrollbar flex flex-grow overflow-scroll">
        <div class="order_detail_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
            <table class="text-xs">
                <thead>
                    <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                        @if($has_lots)
                            <th class="font-thin py-1 px-2 text-center">LOT</th>
                        @endif
                        <th class="font-thin py-1 px-2 text-center">商品画像</th>
                        <th class="font-thin py-1 px-2 text-center">商品引当</th>
                        <th class="font-thin py-1 px-2 text-center">在庫引当</th>
                        <th class="font-thin py-1 px-2 text-center">商品コード</th>
                        <th class="font-thin py-1 px-2 text-center">商品JANコード</th>
                        <th class="font-thin py-1 px-2 text-center">商品名</th>
                        <th class="font-thin py-1 px-2 text-center">出荷数</th>
                        <th class="font-thin py-1 px-2 text-center">商品単価</th>
                        <th class="font-thin py-1 px-2 text-center">引当残</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($order->order_items as $order_item)
                        <tr class="text-left cursor-default whitespace-nowrap">
                            @if($has_lots)
                                <td class="py-1 px-2 border text-center">
                                    @if($order_item->order_item_lots->isNotEmpty())
                                        <i class="las la-angle-right la-lg cursor-pointer lot_accordion_toggle" data-target="lot_detail_{{ $order_item->order_item_id }}"></i>
                                    @endif
                                </td>
                            @endif
                            <td class="py-1 px-2 border">
                                @if($image = $order_item->item?->item_image_file_name)
                                    <img src="{{ asset('storage/item_images/'.$image) }}" class="w-10 h-10 mx-auto image_fade_in_modal_open">
                                @endif
                            </td>
                            <td class="py-1 px-2 border text-center">{!! displayCheckIfTrue($order_item->is_item_allocated) !!}</td>
                            <td class="py-1 px-2 border text-center">{!! displayCheckIfTrue($order_item->is_stock_allocated) !!}</td>
                            <td class="py-1 px-2 border">{{ $order_item->order_item_code }}</td>
                            <td class="py-1 px-2 border">{{ $order_item->item?->item_jan_code }}</td>
                            <td class="py-1 px-2 border text-right">{{ number_format($order_item->shipping_quantity) }}</td>
                            <td class="py-1 px-2 border text-right">{{ number_format($order_item->order_item_unit_price) }}</td>
                            <td class="py-1 px-2 border text-right">{{ number_format($order_item->unallocated_quantity) }}</td>
                        </tr>
                        @if($order_item->order_item_lots->isNotEmpty())
                            <tr id="lot_detail_{{ $order_item->order_item_id }}" class="hidden bg-gray-50">
                                <td colspan="10" class="py-2 px-4 border">
                                    <table class="text-xs border ">
                                        <thead>
                                            <tr class="text-left text-white bg-gray-600">
                                                <th class="font-thin py-1 px-2">LOT</th>
                                                <th class="font-thin py-1 px-2">EXP</th>
                                                <th class="font-thin py-1 px-2 text-right">数量</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order_item->order_item_lots as $lot)
                                                <tr>
                                                    <td class="py-1 px-2 border">{{ $lot->lot }}</td>
                                                    <td class="py-1 px-2 border">{{ formatExp($lot->exp) }}</td>
                                                    <td class="py-1 px-2 border text-right">{{ number_format($lot->quantity) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>