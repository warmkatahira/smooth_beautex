<div id="shipping_group_select_div" class="flex flex-col border border-green-500 bg-green-100 text-gray-600 text-sm px-10 py-5 my-3">
    <div class="flex">
        <form method="GET" action="{{ route('shipping_mgt.index') }}" id="shipping_group_select_form" class="m-0">
            <select id="search_shipping_group_id" name="search_shipping_group_id" class="text-sm py-0">
                <option value="" @if(session('search_shipping_group_id') == 0) selected @endif>全て</option>
                @foreach($shippingGroups as $shipping_group)
                    <option value="{{ $shipping_group->shipping_group_id }}" @if(session('search_shipping_group_id') == $shipping_group->shipping_group_id) selected @endif>{{ $shipping_group->shipping_group_name.'[出荷倉庫：'.$shipping_group->base->base_name.'][出荷予定日：'.CarbonImmutable::parse($shipping_group->estimated_shipping_date)->isoFormat('MM月DD日').']' }}</option>
                @endforeach
            </select>
        </form>
        <p class="text-sm ml-1 pt-0.5">の出荷グループを表示中</p>
        @can('warm_check')
            @if(!is_null(session('search_shipping_group_id')))
                <div class="ml-auto">
                    <button id="shipping_group_update_modal_open" class="text-sm text-blue-500"><i class="las la-pen la-lg mr-1"></i>出荷グループ更新</a>
                </div>
            @endif
        @endcan
    </div>
    @can('warm_check')
        @if(!is_null(session('search_shipping_group_id')))
            <div class="mt-5">
                <a href="{{ route('total_picking_list_create.create') }}" class="text-sm text-blue-500 mr-10" target="_blank"><i class="las la-clipboard-list mr-1 la-lg"></i>トータルピッキングリスト</a>
                <a href="{{ route('nifuda_download.index') }}" class="text-sm text-blue-500"><i class="las la-download mr-1 la-lg"></i>荷札データダウンロード</a>
            </div>
            @foreach($shippingMethods as $shipping_method)
                <div class="flex mt-5 border-b border-gray-600 pb-2">
                    <p class="text-sm mr-10 w-44">{{ $shipping_method->delivery_company.' '.$shipping_method->shipping_method }}</p>
                    <div class="flex flex-row">
                        <a href="{{ route('order_document.index', ['shipping_method_id' => $shipping_method->shipping_method_id]) }}" class="text-sm text-blue-500 mr-10"><i class="las la-clipboard-list mr-1 la-lg"></i>個別帳票</a>
                        <a href="{{ route('nifuda_create.create', ['shipping_method_id' => $shipping_method->shipping_method_id]) }}" class="nifuda_issue text-sm text-blue-500"><i class="las la-file mr-1 la-lg"></i>荷札データ作成</a>
                    </div>
                </div>
            @endforeach
        @endif
    @endcan
</div>