<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="order_mgt_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table id="filter_table" class="text-xs" data-search-url="/order_mgt" data-scroll-target=".order_mgt_list" data-extra-params='{"filter_order_status_id": ""}'>
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0 h-7 z-10">
                    <th id="all_check" class="font-thin py-1 px-2"><i class="las la-check-square la-lg"></i></th>
                    <th class="font-thin py-1 px-2 text-center">操作</th>
                    <th class="font-thin py-1 px-2 text-center">注文ステータス</th>
                    <th class="font-thin py-1 px-2 text-center">取込日</th>
                    <th class="font-thin py-1 px-2 text-center">取込時間</th>
                    <th class="font-thin py-1 px-2 text-center">注文番号</th>
                    <th class="font-thin py-1 px-2 text-center">注文日</th>
                    <th class="font-thin py-1 px-2 text-center">注文時間</th>
                    <th class="font-thin py-1 px-2 text-center">受注管理ID</th>
                    <th class="font-thin py-1 px-2 text-center">受注マーク</th>
                    <th class="font-thin py-1 px-2 text-center">受注区分</th>
                    <th class="font-thin py-1 px-2 text-center">モール</th>
                    <th class="font-thin py-1 px-2 text-center">出荷倉庫</th>
                    <th class="font-thin py-1 px-2 text-center">配送先名</th>
                    <th class="font-thin py-1 px-2 text-center">配送地域</th>
                    <th class="font-thin py-1 px-2 text-center">配送先都道府県</th>
                    <th class="font-thin py-1 px-2 text-center">運送会社</th>
                    <th class="font-thin py-1 px-2 text-center">配送方法</th>
                    <th class="font-thin py-1 px-2 text-center">配送希望日</th>
                    <th class="font-thin py-1 px-2 text-center">配送希望時間</th>
                    <th class="font-thin py-1 px-2 text-center">再発送</th>
                </tr>
                <tr class="filter-row sticky top-[28px] bg-white z-10">
                    <th></th>
                    <th></th>
                    <th></th>
                    <x-filter.input type="date" id="filter_order_import_date" name="filter_order_import_date" />
                    <x-filter.input type="tel" id="filter_order_import_time" name="filter_order_import_time" />
                    <x-filter.input type="tel" id="filter_order_no" name="filter_order_no" />
                    <x-filter.input type="date" id="filter_order_date" name="filter_order_date" />
                    <x-filter.input type="tel" id="filter_order_time" name="filter_order_time" />
                    <x-filter.input type="tel" id="filter_order_control_id" name="filter_order_control_id" />
                    <x-filter.datalist listId="order_mark" id="filter_order_mark" name="filter_order_mark" :selectItems="$orderMarks" optionValue="order_mark" />
                    <x-filter.select-mall id="filter_order_category_id" name="filter_order_category_id" :malls="$malls" />
                    <x-filter.select id="filter_mall_id" name="filter_mall_id" :selectItems="$malls" optionValue="mall_id" optionText="mall_name" />
                    <x-filter.select id="filter_shipping_base_id" name="filter_shipping_base_id" :selectItems="$bases" optionValue="base_id" optionText="base_name" />
                    <x-filter.input type="text" id="filter_ship_name" name="filter_ship_name" />
                    <x-filter.select-array id="filter_ship_region_type" name="filter_ship_region_type" :items="$shipRegionTypes" />
                    <x-filter.select id="filter_ship_province_name" name="filter_ship_province_name" :selectItems="$prefectures" optionValue="prefecture_name" optionText="prefecture_name" />
                    <x-filter.select id="filter_shipping_delivery_company_id" name="filter_shipping_delivery_company_id" :selectItems="$deliveryCompanies" optionValue="delivery_company_id" optionText="delivery_company" />
                    <x-filter.select-delivery-company id="filter_shipping_method_id" name="filter_shipping_method_id" :deliveryCompanies="$deliveryCompanies" />
                    <x-filter.input type="date" id="filter_desired_delivery_date" name="filter_desired_delivery_date" />
                    <x-filter.input type="tel" id="filter_desired_delivery_time" name="filter_desired_delivery_time" />
                    <x-filter.select-boolean id="filter_is_redelivery" name="filter_is_redelivery" label1="対象" label0="対象外" />
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($orders as $order)
                    <tr class="text-left cursor-default whitespace-nowrap">
                        <td class="py-1 px-2 border">
                            <input type="checkbox" name="chk[]" value="{{ $order->order_control_id }}" form="operation_div_form">
                        </td>
                        <td class="py-1 px-2 border">
                            <div class="flex flex-row gap-5">
                                <a href="{{ route('order_detail.index', ['order_control_id' => $order->order_control_id]) }}" class="btn rounded bg-btn-enter text-white py-1 px-2">詳細</a>
                            </div>
                        </td>
                        <td class="py-1 px-2 border text-center">{{ OrderStatusEnum::getJpValueById($order->order_status_id) }}</td>
                        <td class="py-1 px-2 border text-center">{{ CarbonImmutable::parse($order->order_import_date)->isoFormat('Y年MM月DD日(ddd)') }}</td>
                        <td class="py-1 px-2 border text-center">{{ CarbonImmutable::parse($order->order_import_time)->isoFormat('HH:mm:ss') }}</td>
                        <td class="py-1 px-2 border text-center relative group/clipboard">
                            {{ $order->order_no }}
                            <x-clipboard-copy-btn :value="$order->order_no" label="注文番号" />
                        </td>
                        <td class="py-1 px-2 border text-center">{{ CarbonImmutable::parse($order->order_date)->isoFormat('Y年MM月DD日(ddd)') }}</td>
                        <td class="py-1 px-2 border text-center">{{ CarbonImmutable::parse($order->order_time)->isoFormat('HH:mm:ss') }}</td>
                        <td class="py-1 px-2 border text-center relative group/clipboard">
                            {{ $order->order_control_id }}
                            <x-clipboard-copy-btn :value="$order->order_control_id" label="受注管理ID" />
                        </td>
                        <td class="py-1 px-2 border text-center relative group/clipboard">
                            {{ $order->order_mark }}
                            <x-clipboard-copy-btn :value="$order->order_mark" label="受注マーク" />
                        </td>
                        <td class="py-1 px-2 border text-center">{{ $order->order_category->order_category_name }}</td>
                        <td class="py-1 px-2 border text-center">
                            <img src="{{ asset('image/'.$order->order_category->mall->mall_image_file_name) }}" class="w-12 inline-block">
                        </td>
                        <td class="py-1 px-2 border text-center">{{ $order->base?->base_name }}</td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $order->ship_name }}
                            <x-clipboard-copy-btn :value="$order->ship_name" label="配送先名" />
                        </td>
                        <td class="py-1 px-2 border text-center">{{ $order->ship_region_type }}</td>
                        <td class="py-1 px-2 border text-center">{{ $order->ship_province_name }}</td>
                        <td class="py-1 px-2 border text-center">
                            @if($image = $order->shipping_method?->delivery_company?->company_image)
                                <img src="{{ asset('image/'.$image) }}" class="h-8 w-auto inline-block">
                            @endif
                        </td>
                        <td class="py-1 px-2 border text-center">{{ $order->shipping_method?->shipping_method }}</td>
                        <td class="py-1 px-2 border text-center">
                            @if(!is_null($order->desired_delivery_date))
                                {{ CarbonImmutable::parse($order->desired_delivery_date)->isoFormat('Y年MM月DD日(ddd)') }}
                            @endif
                        </td>
                        <td class="py-1 px-2 border">{{ $order->desired_delivery_time }}</td>
                        <td class="py-1 px-2 border text-center">
                            <x-list.status :value="$order->is_redelivery" label1="対象" label0="対象外" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>