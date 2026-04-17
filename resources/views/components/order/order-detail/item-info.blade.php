@php
    // LOTが存在するか確認
    $has_lots = $order->order_items->contains(fn($item) => $item->order_item_lots->isNotEmpty());
    // LOT照合を表示するか（出荷済みは非表示）
    $show_shipping_inspection_lot = $has_lots && $order->order_status_id < OrderStatusEnum::SHUKKA_ZUMI;
    // 出荷検品で取得したロット×EXPを編集できるかを取得
    $editable_lots = $order->is_shipping_inspection_complete === 1 && $order->order_status_id === OrderStatusEnum::SAGYO_CHU;
    // colspanを動的に計算
    $colspan = 10; // 固定列数
    if($order->order_status_id <= OrderStatusEnum::SHUKKA_MACHI) $colspan++;    // 操作列
    if($order->order_status_id == OrderStatusEnum::SAGYO_CHU) $colspan++;       // 1個口料金オーバー列
    if($has_lots) $colspan++;                                                   // LOT列
    if($show_shipping_inspection_lot) $colspan++;                               // LOT照合列
@endphp

<div>
    <div class="flex flex-row items-start">
        <p class="text-base font-semibold pb-2 mb-4">商品情報</p>
        @if($order->order_status_id <= OrderStatusEnum::SHUKKA_MACHI)
            @if($order->is_redelivery)
                @can('warm_check')
                    <button type="button" id="past_order_reference_modal_open" class="btn bg-green-600 text-white ml-auto mr-5 px-5 py-1 rounded-md">過去注文から商品情報を引用</button>
                @endcan
                <button type="button" id="order_item_create_modal_open" class="btn bg-btn-enter text-white px-5 py-1 rounded-md">商品追加</button>
            @else
                <button type="button" id="order_item_create_modal_open" class="btn bg-btn-enter text-white ml-auto px-5 py-1 rounded-md">商品追加</button>
            @endif
        @endif
    </div>
    <div class="disable_scrollbar flex flex-grow overflow-scroll">
        <div class="order_detail_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
            <table class="text-xs">
                <thead>
                    <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                        @if($order->order_status_id <= OrderStatusEnum::SHUKKA_MACHI)
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
                        @if($order->order_status_id == OrderStatusEnum::SAGYO_CHU)
                            <th class="font-thin py-1 px-2 text-center">1個口料金オーバー</th>
                        @endif
                        @if($has_lots)
                            @if($show_shipping_inspection_lot)
                                <th class="font-thin py-1 px-2 text-center">LOT照合</th>
                            @endif
                            <th class="font-thin py-1 px-2 text-center">LOT</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($order->order_items as $order_item)
                        <tr class="text-left cursor-default whitespace-nowrap">
                            @if($order->order_status_id <= OrderStatusEnum::SHUKKA_MACHI)
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
                            <td class="py-1 px-2 border relative group/clipboard whitespace-normal max-w-lg">
                                {{ $order_item->item?->item_name ?? $order_item->order_item_name }}
                                <x-clipboard-copy-btn :value="$order_item->item?->item_name ?? $order_item->order_item_name" label="商品名" />
                            </td>
                            <td class="py-1 px-2 border text-right">{{ number_format($order_item->shipping_quantity) }}</td>
                            <td class="py-1 px-2 border text-right">{{ number_format($order_item->order_item_unit_price) }}</td>
                            <td class="py-1 px-2 border text-right">{{ number_format($order_item->unallocated_quantity) }}</td>
                            @if($order->order_status_id == OrderStatusEnum::SAGYO_CHU)
                                <td class="py-1 px-2 border text-center">
                                    <x-list.status :value="$order_item->is_over_threshold" label1="対象" label0="対象外" />
                                    @if($order_item->is_over_threshold)
                                        @php
                                            $unit = floor($order_item->order_item_unit_price / 1.6);
                                            $price = $unit * $order_item->shipping_quantity;
                                        @endphp
                                        <div class="text-gray-500 mt-0.5 flex flex-col gap-1">
                                            <p>¥{{ number_format($order_item->order_item_unit_price) }} ÷ 1.6 = ¥{{ number_format($unit) }}</p>
                                            <p>¥{{ number_format($unit) }} × {{ number_format($order_item->shipping_quantity) }} = ¥{{ number_format($price) }}</p>
                                        </div>
                                        <button type="button"
                                            class="btn bg-yellow-500 text-white px-2 py-0.5 rounded mt-1 text-xs split_preview_modal_open"
                                            data-order-item-id="{{ $order_item->order_item_id }}">
                                            分割
                                        </button>
                                    @endif
                                </td>
                            @endif
                            @if($has_lots)
                                @if($show_shipping_inspection_lot)
                                    <td class="py-1 px-2 border text-center">
                                        @if($order_item->order_item_lots->isEmpty() || $order_item->order_item_lots->every(fn($lot) => is_null($lot->lot)))
                                            <span class="text-gray-300 text-xs">-</span>
                                        @elseif($order_item->order_item_lots->contains(fn($lot) => $lot->is_valid === false))
                                            <span class="inline-block bg-status-ng-bg text-status-ng-text text-xs font-bold px-2 py-0.5 rounded">NG</span>
                                        @else
                                            <span class="inline-block bg-status-ok-bg text-status-ok-text text-xs font-bold px-2 py-0.5 rounded">OK</span>
                                        @endif
                                    </td>
                                @endif
                                <td class="py-1 px-2 border text-center">
                                    @if($order_item->order_item_lots->isNotEmpty())
                                        <i class="las la-angle-right la-lg cursor-pointer lot_accordion_toggle" data-target="lot_detail_{{ $order_item->order_item_id }}"></i>
                                    @endif
                                </td>
                            @endif
                        </tr>
                        @if($order_item->order_item_lots->isNotEmpty())
                            <tr id="lot_detail_{{ $order_item->order_item_id }}" class="hidden bg-gray-50">
                                <td colspan="{{ $colspan }}" class="py-2 px-4 border">
                                    @if($editable_lots && $order_item->item?->is_lot_managed)
                                        <form method="POST" action="{{ route('order_item_lot_update.update') }}" id="order_item_lot_update_form_{{ $order_item->order_item_id }}">
                                            @csrf
                                    @endif
                                    <table class="text-xs border">
                                        <thead>
                                            <tr class="text-left text-white bg-gray-600">
                                                <th class="font-thin py-1 px-2">LOT</th>
                                                <th class="font-thin py-1 px-2">EXP</th>
                                                <th class="font-thin py-1 px-2 text-right">数量</th>
                                                @if($show_shipping_inspection_lot)
                                                    <th class="font-thin py-1 px-2 text-center">LOT照合</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order_item->order_item_lots as $lot)
                                                <tr>
                                                    @if($editable_lots && $order_item->item?->is_lot_managed)
                                                        <input type="hidden" name="lots[{{ $loop->index }}][order_item_lot_id]" value="{{ $lot->order_item_lot_id }}">
                                                        <td class="py-1 px-2 border">
                                                            <input type="tel" name="lots[{{ $loop->index }}][lot]" class="py-0.5 px-2 text-xs" value="{{ $lot->lot }}" autocomplete="off">
                                                        </td>
                                                        <td class="py-1 px-2 border">
                                                            <input type="tel" name="lots[{{ $loop->index }}][exp]" class="py-0.5 px-2 text-xs" value="{{ $lot->exp }}" autocomplete="off">
                                                        </td>
                                                        <td class="py-1 px-2 border text-right">
                                                            <input type="number" name="lots[{{ $loop->index }}][quantity]" class="py-0.5 px-2 text-xs text-right w-20" value="{{ $lot->quantity }}" min="1" autocomplete="off">
                                                        </td>
                                                    @else
                                                        <td class="py-1 px-2 border text-center">{{ $lot->lot }}</td>
                                                        <td class="py-1 px-2 border text-center">{{ formatExp($lot->exp) }}</td>
                                                        <td class="py-1 px-2 border text-right">{{ number_format($lot->quantity) }}</td>
                                                    @endif
                                                    @if($show_shipping_inspection_lot)
                                                        <td class="py-1 px-2 border text-center">
                                                            @if(is_null($lot->lot))
                                                                <span class="text-gray-300 text-xs">-</span>
                                                            @elseif($lot->is_valid)
                                                                <span class="inline-block bg-status-ok-bg text-status-ok-text text-xs font-bold px-2 py-0.5 rounded">OK</span>
                                                            @else
                                                                <span class="inline-block bg-status-ng-bg text-status-ng-text text-xs font-bold px-2 py-0.5 rounded">NG</span>
                                                            @endif
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if($editable_lots && $order_item->item?->is_lot_managed)
                                        <div class="flex justify-start mt-2">
                                            <button type="button" class="btn order_item_lot_update_enter bg-btn-enter text-white px-4 py-1 rounded-md text-xs" data-order-item-id="{{ $order_item->order_item_id }}">保存</button>
                                        </div>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <form method="POST" action="{{ route('order_item_delete.delete') }}" id="order_item_delete_form">
        @csrf
        <input type="hidden" id="order_item_id" name="order_item_id">
    </form>
    <form method="POST" action="{{ route('order_item_split.split') }}" id="order_item_split_form">
        @csrf
        <input type="hidden" id="split_order_item_id" name="order_item_id">
    </form>
</div>
<div id="split_preview_modal" class="split_preview_modal_close hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-96">
        <p class="font-semibold mb-4">分割プレビュー</p>
        <div id="split_preview_content" class="text-sm mb-4"></div>
        <div class="flex justify-end gap-3">
            <button type="button" id="split_confirm" class="btn bg-btn-enter text-white px-4 py-1 rounded">確定</button>
        </div>
    </div>
</div>