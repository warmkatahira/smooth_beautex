<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="shipping_mgt_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table id="filter_table" class="text-xs" data-search-url="/shipping_mgt" data-scroll-target=".shipping_mgt_list" data-extra-params='{"filter_order_status_id": ""}'>
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0 h-7 z-10">
                    @can('warm_check')
                        <th id="all_check" class="font-thin py-1 px-2"><i class="las la-check-square la-lg"></i></th>
                    @endcan
                    <th class="font-thin py-1 px-2 text-center">操作</th>
                    <th class="font-thin py-1 px-2 text-center">取込日</th>
                    <th class="font-thin py-1 px-2 text-center">取込時間</th>
                    <th class="font-thin py-1 px-2 text-center">注文番号</th>
                    <th class="font-thin py-1 px-2 text-center">注文日</th>
                    <th class="font-thin py-1 px-2 text-center">注文時間</th>
                    <th class="font-thin py-1 px-2 text-center">受注管理ID</th>
                    <th class="font-thin py-1 px-2 text-center">補足事項</th>
                    <th class="font-thin py-1 px-2 text-center">受注区分</th>
                    <th class="font-thin py-1 px-2 text-center">モール</th>
                    <th class="font-thin py-1 px-2 text-center">出荷倉庫</th>
                    <th class="font-thin py-1 px-2 text-center">配送先名</th>
                    <th class="font-thin py-1 px-2 text-center">配送地域</th>
                    <th class="font-thin py-1 px-2 text-center">配送先国名コード</th>
                    <th class="font-thin py-1 px-2 text-center">配送先都道府県</th>
                    <th class="font-thin py-1 px-2 text-center">運送会社</th>
                    <th class="font-thin py-1 px-2 text-center">配送方法</th>
                    <th class="font-thin py-1 px-2 text-center">配送方法変更</th>
                    <th class="font-thin py-1 px-2 text-center">配送希望日</th>
                    <th class="font-thin py-1 px-2 text-center">配送希望時間</th>
                    <th class="font-thin py-1 px-2 text-center">出荷個口No</th>
                    <th class="font-thin py-1 px-2 text-center">配送伝票番号</th>
                    @can('warm_check')
                        <th class="font-thin py-1 px-2 text-center">出荷検品状態</th>
                        <th class="font-thin py-1 px-2 text-center">出荷検品完了日時</th>
                        <th class="font-thin py-1 px-2 text-center">出荷検品LOT</th>
                    @endcan
                </tr>
                <tr class="filter-row sticky top-[28px] bg-white z-10">
                    @can('warm_check')
                        <th></th>
                    @endcan
                    <th></th>
                    <x-filter.date-period type="date" fromId="filter_order_import_date_from" fromName="filter_order_import_date_from" toId="filter_order_import_date_to" toName="filter_order_import_date_to" />
                    <x-filter.input type="tel" id="filter_order_import_time" name="filter_order_import_time" />
                    <x-filter.input type="tel" id="filter_order_no" name="filter_order_no" />
                    <x-filter.date-period type="date" fromId="filter_order_date_from" fromName="filter_order_date_from" toId="filter_order_date_to" toName="filter_order_date_to" />
                    <x-filter.input type="tel" id="filter_order_time" name="filter_order_time" />
                    <x-filter.input type="tel" id="filter_order_control_id" name="filter_order_control_id" />
                    <x-filter.select-boolean id="filter_has_supplement" name="filter_has_supplement" label1="あり" label0="なし" />
                    <x-filter.select-mall id="filter_order_category_id" name="filter_order_category_id" :malls="$malls" />
                    <x-filter.select id="filter_mall_id" name="filter_mall_id" :selectItems="$malls" optionValue="mall_id" optionText="mall_name" />
                    <x-filter.select id="filter_shipping_base_id" name="filter_shipping_base_id" :selectItems="$bases" optionValue="base_id" optionText="base_name" />
                    <x-filter.input type="text" id="filter_ship_name" name="filter_ship_name" />
                    <x-filter.select-array id="filter_ship_region_type" name="filter_ship_region_type" :items="$shipRegionTypes" />
                    <x-filter.input type="text" id="filter_ship_province_code" name="filter_ship_country_code" />
                    <x-filter.select id="filter_ship_province_name" name="filter_ship_province_name" :selectItems="$prefectures" optionValue="prefecture_name" optionText="prefecture_name" />
                    <x-filter.select id="filter_shipping_delivery_company_id" name="filter_shipping_delivery_company_id" :selectItems="$deliveryCompanies" optionValue="delivery_company_id" optionText="delivery_company" />
                    <x-filter.select-delivery-company id="filter_shipping_method_id" name="filter_shipping_method_id" :deliveryCompanies="$deliveryCompanies" />
                    <x-filter.select-boolean id="filter_is_shipping_method_changed" name="filter_is_shipping_method_changed" label1="あり" label0="なし" />
                    <x-filter.date-period type="date" fromId="filter_desired_delivery_date_from" fromName="filter_desired_delivery_date_from" toId="filter_desired_delivery_date_to" toName="filter_desired_delivery_date_to" />
                    <x-filter.input type="tel" id="filter_desired_delivery_time" name="filter_desired_delivery_time" />
                    <x-filter.input type="tel" id="filter_package_count" name="filter_package_count" />
                    <x-filter.input type="tel" id="filter_tracking_no" name="filter_tracking_no" />
                    @can('warm_check')
                        <x-filter.select-boolean id="filter_is_shipping_inspection_complete" name="filter_is_shipping_inspection_complete" label1="実施済" label0="未実施" />
                        <th></th>
                        <x-filter.select-boolean id="filter_shipping_inspection_lot" name="filter_shipping_inspection_lot" label1="NG" label0="OK" />
                    @endcan
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($orders as $order)
                    <tr class="text-left cursor-default whitespace-nowrap">
                        @can('warm_check')
                            <td class="py-1 px-2 border">
                                <input type="checkbox" name="chk[]" value="{{ $order->order_control_id }}" form="operation_div_form">
                            </td>
                        @endcan
                        <td class="py-1 px-2 border">
                            <div class="flex flex-row gap-5">
                                <a href="{{ route('order_detail.index', ['order_control_id' => $order->order_control_id]) }}" class="btn rounded bg-btn-enter text-white py-1 px-2">詳細</a>
                            </div>
                        </td>
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
                        <td class="py-1 px-2 border text-center">
                            <x-list.status :value="!empty($order->supplement)" label1="あり" label0="なし" />
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
                        <td class="py-1 px-2 border text-center">{{ $order->ship_country_code }}</td>
                        <td class="py-1 px-2 border text-center">{{ $order->ship_prefecture_name }}</td>
                        <td class="py-1 px-2 border text-center">
                            @if($image = $order->shipping_method?->delivery_company?->company_image)
                                <img src="{{ asset('image/'.$image) }}" class="h-8 w-auto inline-block">
                            @endif
                        </td>
                        <td class="py-1 px-2 border text-center">{{ $order->shipping_method->shipping_method }}</td>
                        <td class="py-1 px-2 border text-center">
                            <x-list.status :value="$order->is_shipping_method_changed" label1="あり" label0="なし" />
                        </td>
                        <td class="py-1 px-2 border text-center">
                            @if(!is_null($order->desired_delivery_date))
                                {{ CarbonImmutable::parse($order->desired_delivery_date)->isoFormat('Y年MM月DD日(ddd)') }}
                            @endif
                        </td>
                        <td class="py-1 px-2 border">{{ $order->desired_delivery_time }}</td>
                        <td class="py-1 px-2 border text-right">{{ $order->package_count }}</td>
                        <td class="py-1 px-2 border text-center">
                            @foreach(TrackingNoUrlMakeFunc::make($order) as $key => $value)
                                <a href="{{ $value }}" class="underline text-blue-500" target="_blank" rel="noopener noreferrer">{{ $key }}</a>
                            @endforeach
                        </td>
                        @can('warm_check')
                            <td class="py-1 px-2 border text-center">
                                <x-list.status :value="$order->is_shipping_inspection_complete" label1="実施済" label0="未実施" />
                            </td>
                            <td class="py-1 px-2 border text-center">
                                @if(!is_null($order->shipping_inspection_date))
                                    {{ CarbonImmutable::parse($order->shipping_inspection_date)->isoFormat('Y年MM月DD日(ddd) HH:mm:ss') }}
                                @endif
                            </td>
                            <td class="py-1 px-2 border text-center">
                                @if($order->is_shipping_inspection_complete)
                                    @if($order->invalid_lot_count > 0)
                                        <span class="inline-block bg-status-ng-bg text-status-ng-text text-xs font-bold px-2 py-0.5 rounded">NG</span>
                                    @else
                                        <span class="inline-block bg-status-ok-bg text-status-ok-text text-xs font-bold px-2 py-0.5 rounded">OK</span>
                                    @endif
                                @else
                                    <span class="text-gray-300 text-xs">-</span>
                                @endif
                            </td>
                        @endcan
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>