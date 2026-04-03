@php
    // LOTが存在するか確認
    $has_lots = $order->order_items->contains(fn($item) => $item->order_item_lots->isNotEmpty());
@endphp

<div>
    <div class="flex flex-row items-start">
        <p class="text-base font-semibold pb-2 mb-4">商品情報</p>
        @if($order->order_status_id <= OrderStatusEnum::SHUKKA_MACHI)
            <button type="button" id="order_item_create_modal_open" class="btn bg-btn-enter text-white ml-auto px-5 py-1 rounded-md">商品追加</button>
        @endif
    </div>
    <div class="disable_scrollbar flex flex-grow overflow-scroll">
        <div class="order_detail_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
            <table class="text-xs">
                <thead>
                    <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                        @if($order->order_status_id < OrderStatusEnum::SHUKKA_ZUMI)
                            <th class="font-thin py-1 px-2 text-center">操作</th>
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
                        @if($has_lots)
                            <th class="font-thin py-1 px-2 text-center">LOT</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($order->order_items as $order_item)
                        <tr class="text-left cursor-default whitespace-nowrap">
                            @if($order->order_status_id < OrderStatusEnum::SHUKKA_ZUMI)
                                <td class="py-1 px-2 border">
                                    <button type="button" class="btn order_item_delete_enter rounded bg-btn-cancel text-white py-1 px-2" data-order-item-id="{{ $order_item->order_item_id }}">削除</a>
                                </td>
                            @endif
                            <td class="py-1 px-2 border">
                                @if($image = $order_item->item?->item_image_file_name)
                                    <img src="{{ asset('storage/item_images/'.$image) }}" class="w-10 h-10 mx-auto image_fade_in_modal_open">
                                @endif
                            </td>
                            <td class="py-1 px-2 border text-center">{!! displayCheckIfTrue($order_item->is_item_allocated) !!}</td>
                            <td class="py-1 px-2 border text-center">{!! displayCheckIfTrue($order_item->is_stock_allocated) !!}</td>
                            <td class="py-1 px-2 border relative group/clipboard">
                                {{ $order_item->order_item_code }}
                                <x-clipboard-copy-btn :value="$order_item->order_item_code" label="商品コード" />
                            </td>
                            <td class="py-1 px-2 border relative group/clipboard">
                                {{ $order_item->item?->item_jan_code }}
                                <x-clipboard-copy-btn :value="$order_item->item?->item_jan_code" label="商品JANコード" />
                            </td>
                            <td class="py-1 px-2 border relative group/clipboard">
                                {{ $order_item->item?->item_name ?? $order_item->order_item_name }}
                                <x-clipboard-copy-btn :value="$order_item->item?->item_name ?? $order_item->order_item_name" label="商品名" />
                            </td>
                            <td class="py-1 px-2 border text-right">{{ number_format($order_item->shipping_quantity) }}</td>
                            <td class="py-1 px-2 border text-right">{{ number_format($order_item->order_item_unit_price) }}</td>
                            <td class="py-1 px-2 border text-right">{{ number_format($order_item->unallocated_quantity) }}</td>
                            @if($has_lots)
                                <td class="py-1 px-2 border text-center">
                                    @if($order_item->order_item_lots->isNotEmpty())
                                        <i class="las la-angle-right la-lg cursor-pointer lot_accordion_toggle" data-target="lot_detail_{{ $order_item->order_item_id }}"></i>
                                    @endif
                                </td>
                            @endif
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
<form method="POST" action="{{ route('order_item_delete.delete') }}" id="order_item_delete_form">
    @csrf
    <input type="hidden" id="order_item_id" name="order_item_id">
</form>