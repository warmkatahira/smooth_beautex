<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="shipping_mgt_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th id="all_check" class="font-thin py-1 px-2"><i class="las la-check-square la-lg"></i></th>
                    <th class="font-thin py-1 px-2 text-center">操作</th>
                    <th class="font-thin py-1 px-2 text-center">取込日</th>
                    <th class="font-thin py-1 px-2 text-center">取込時間</th>
                    <th class="font-thin py-1 px-2 text-center">注文番号</th>
                    <th class="font-thin py-1 px-2 text-center">注文日</th>
                    <th class="font-thin py-1 px-2 text-center">受注管理ID</th>
                    <th class="font-thin py-1 px-2 text-center">受注区分</th>
                    <th class="font-thin py-1 px-2 text-center">出荷倉庫</th>
                    <th class="font-thin py-1 px-2 text-center">配送先名</th>
                    <th class="font-thin py-1 px-2 text-center">配送先都道府県</th>
                    <th class="font-thin py-1 px-2 text-center">運送会社</th>
                    <th class="font-thin py-1 px-2 text-center">配送方法</th>
                    <th class="font-thin py-1 px-2 text-center">配送希望日</th>
                    <th class="font-thin py-1 px-2 text-center">配送希望時間</th>
                    <th class="font-thin py-1 px-2 text-center">配送伝票番号</th>
                    @can('warm_check')
                        <th class="font-thin py-1 px-2 text-center">出荷検品完了日時</th>
                    @endcan
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
                                <a href="{{ route('order_detail.index', ['order_control_id' => $order->order_control_id]) }}" class="btn bg-btn-enter text-white py-1 px-2">詳細</a>
                            </div>
                        </td>
                        <td class="py-1 px-2 border text-center">{{ CarbonImmutable::parse($order->order_import_date)->isoFormat('Y年MM月DD日(ddd)') }}</td>
                        <td class="py-1 px-2 border text-center">{{ CarbonImmutable::parse($order->order_import_time)->isoFormat('HH:mm:ss') }}</td>
                        <td class="py-1 px-2 border text-center">{{ $order->order_no }}</td>
                        <td class="py-1 px-2 border text-center">{{ CarbonImmutable::parse($order->order_date)->isoFormat('Y年MM月DD日(ddd)') }}</td>
                        <td class="py-1 px-2 border text-center">{{ $order->order_control_id }}</td>
                        <td class="py-1 px-2 border text-center">
                            <img src="{{ asset('image/'.$order->order_category->order_category_image_file_name) }}" class="w-12 inline-block">
                        </td>
                        <td class="py-1 px-2 border">{{ $order->base?->base_name }}</td>
                        <td class="py-1 px-2 border">{{ $order->ship_name }}</td>
                        <td class="py-1 px-2 border text-center">{{ $order->ship_prefecture_name }}</td>
                        <td class="py-1 px-2 border">
                            <img src="{{ asset('image/'.$order->shipping_method->delivery_company->company_image) }}" class="inline-block">
                        </td>
                        <td class="py-1 px-2 border">{{ $order->shipping_method->shipping_method }}</td>
                        <td class="py-1 px-2 border text-center">
                            @if(!is_null($order->desired_delivery_date))
                                {{ CarbonImmutable::parse($order->desired_delivery_date)->isoFormat('Y年MM月DD日(ddd)') }}
                            @endif
                        </td>
                        <td class="py-1 px-2 border">{{ $order->desired_delivery_time }}</td>
                        <td class="py-1 px-2 border text-center">
                            @foreach(TrackingNoUrlMakeFunc::make($order) as $key => $value)
                                <a href="{{ $value }}" class="underline text-blue-500" target="_blank" rel="noopener noreferrer">{{ $key }}</a>
                            @endforeach
                        </td>
                        @can('warm_check')
                            <td class="py-1 px-2 border text-center">
                                @if(!is_null($order->shipping_inspection_date))
                                    {{ CarbonImmutable::parse($order->shipping_inspection_date)->isoFormat('Y年MM月DD日(ddd) HH:mm:ss') }}
                                @endif
                            </td>
                        @endcan
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>