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
                <div class="flex justify-between items-start">
                    <span>発行日時：{{ CarbonImmutable::now()->isoFormat('Y/MM/DD HH:mm:ss') }}</span>
                    <span>{!! DNS2D::getBarcodeSVG($order->order_control_id, "QRCODE", 1.5, 1.5, 'black') !!}</span>
                </div>
                <div class="text-center">
                    <span class="text-2xl">個別ピッキングリスト</span>
                </div>
                <!-- US強調表示 -->
                @if($order->ship_country_code === 'US')
                    <div class="my-2 p-2 bg-red-200 border border-red-500 text-center">
                        <span class="text-red-700 font-bold text-lg">アメリカ宛て出荷</span>
                    </div>
                @endif
                <!-- 注文概要 -->
                <div class="my-3 flex flex-row flex-wrap">
                    @php
                        // 変数を初期化
                        $desired_delivery_date = '';
                        // 配送希望日がNull以外の場合
                        if(!is_null($order->desired_delivery_date)){
                            // フォーマットを変更
                            $desired_delivery_date = CarbonImmutable::parse($order->desired_delivery_date)->isoFormat('Y年MM月DD日(ddd)');
                        }
                        // 海外のみ総商品金額を取得
                        if($order->ship_region_type === '海外'){
                            $total_item_price = $order->order_items->sum(function($order_item) use ($order) {
                                $unit_price = $order->ship_country_code == 'US'
                                    ? floor($order_item->order_item_unit_price / 1.6)
                                    : $order_item->order_item_unit_price;
                                return $unit_price * $order_item->shipping_quantity;
                            });
                        }else {
                            $total_item_price = 0;
                        }
                    @endphp
                    <x-shipping.kobetsu-picking-list.info-div label="出荷個口No" :value="$order->package_no_index . '/' . $order->package_no_total" />
                    <x-shipping.kobetsu-picking-list.info-div label="出荷グループ名" :value="$order->shipping_group?->shipping_group_name" />
                    <x-shipping.kobetsu-picking-list.info-div label="注文番号" :value="$order->order_no" />
                    <x-shipping.kobetsu-picking-list.info-div label="受注管理ID" :value="$order->order_control_id" />
                    <x-shipping.kobetsu-picking-list.info-div label="受注区分(モール)" :value="$order->order_category->order_category_name.'('.$order->order_category->mall->mall_name.')'" />
                    <x-shipping.kobetsu-picking-list.info-div label="配送先名" :value="$order->ship_name" />
                    <x-shipping.kobetsu-picking-list.info-div label="配送地域" :value="$order->ship_region_type" />
                    <x-shipping.kobetsu-picking-list.info-div label="総商品金額" :value="$total_item_price" isPrice="true" />
                    <x-shipping.kobetsu-picking-list.info-div label="出荷倉庫" :value="$order->base->base_name" />
                    <x-shipping.kobetsu-picking-list.info-div label="配送方法" :value="$order->delivery_company_and_shipping_method" />
                    <x-shipping.kobetsu-picking-list.info-div label="配送希望日" :value="$desired_delivery_date" />
                    <x-shipping.kobetsu-picking-list.info-div label="配送希望時間" :value="$order->desired_delivery_time" />
                    <x-shipping.kobetsu-picking-list.info-div label="出荷作業メモ" :value="$order->shipping_work_memo" parentDivWidth="w-full" childSpanLabelWidth="w-1/6" childSpanValueWidth="w-5/6" />
                </div>
            </div>
            <!-- 商品明細 -->
            <table class="mt-5 w-full">
                <thead>
                    <tr class="text-left bg-gray-200">
                        <th class="item_jan_code font-thin py-1 px-2 border border-black text-center">JANコード</th>
                        <th class="item_name font-thin py-1 px-2 border border-black text-center">商品名</th>
                        <th class="shipping_quantity font-thin py-1 px-2 border border-black text-center">数量</th>
                    </tr>
                </thead>
                <tbody class="">
                    @foreach($order->order_items as $order_item)
                        <tr class="text-left cursor-default">
                            <td class="item_jan_code py-1 px-2 border border-black text-center">
                                {{ substr($order_item->item->item_jan_code, 0, -4) }}<span class="text-xl font-semibold pl-0.5">{{ substr($order_item->item->item_jan_code, -4) }}</span>
                            </td>
                            <td class="item_name py-1 px-2 border border-black">{{ $order_item->item->item_name}}</td>
                            <td class="shipping_quantity py-1 px-2 border border-black text-right">{{ $order_item->shipping_quantity}}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-200">
                        <td class="py-1 px-2 border border-black text-center" colspan="2">合計</td>
                        <td class="py-1 px-2 border border-black text-right text-2xl">{{ $order->order_items->sum('shipping_quantity') }}</td>
                    </tr>
                </tfoot>
            </table>
        @endforeach
    </div>
</x-document-layout>
@vite(['resources/sass/shipping/document/kobetsu_picking_list.scss'])