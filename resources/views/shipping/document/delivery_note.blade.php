<x-document-layout>
    <div class="page-container">
        @php
            // 変数を初期化
            $order_count = 0;
        @endphp
        @foreach($orders as $order)
            <!-- 最初のページに余計なページがでないように、改ページをコントロールするためのカウント -->
            @php
                // 受注をカウント
                $order_count++;
            @endphp
            <div style="{{ $order_count != 1 ? 'page-break-before: always; padding-top: 0mm;' : '' }}">
                <div class="flex justify-between">
                    <span>{{ $order->order_control_id }}</span>
                    <span class="float-right">{!! DNS2D::getBarcodeSVG($order->order_control_id, "QRCODE", 1.5, 1.5, 'black') !!}</span>
                </div>
                <div class="text-center">
                    <span class="text-2xl">納品明細書</span>
                </div>
                <p class="mt-5">このたびはお買い上げ頂きまして、誠にありがとうございます。</p>
                <div class="mt-5 flex flex-col">
                    <div clas="flex flex-row gap-5">
                        <span>注文番号</span>
                        <span>{{ $order->order_no }}</span>
                    </div>
                    <div clas="flex flex-row gap-5">
                        <span>注文日時</span>
                        <span>{{ CarbonImmutable::parse($order->order_date . ' ' . $order->order_time)->isoFormat('Y/MM/DD HH:mm:ss') }}</span>
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
                    </tr>
                </thead>
                <tbody class="">
                    @foreach($order->order_items as $order_item)
                        <tr class="text-left cursor-default whitespace-nowrap">
                            <td class="item_jan_code py-1 px-2 border border-black text-center">{{ $order_item->item->item_jan_code}}</td>
                            <td class="item_name py-1 px-2 border border-black">{{ $order_item->item->item_name}}</td>
                            <td class="order_quantity py-1 px-2 border border-black text-right">{{ $order_item->order_quantity}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="flex flex-col mt-20">
                <span>{{ $order->shipper->shipper_name }}</span>
                <span>{{ $order->shipper->shipper_zip_code.' '.$order->shipper->shipper_address }}</span>
                <span>{{ $order->shipper->shipper_tel }}</span>
            </div>
        @endforeach
    </div>
</x-document-layout>
@vite(['resources/sass/shipping/document/delivery_note.scss'])