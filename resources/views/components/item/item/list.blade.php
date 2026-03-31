<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="item_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table id="filter_table" class="text-xs" data-search-url="/item" data-scroll-target=".item_list">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0 h-7 z-10">
                    @can('warm_check')
                        <th class="font-thin py-1 px-2 text-center">操作</th>
                    @endcan
                    <th class="font-thin py-1 px-2 text-center">商品画像</th>
                    <th class="font-thin py-1 px-2 text-center">商品コード</th>
                    <th class="font-thin py-1 px-2 text-center">商品JANコード</th>
                    <th class="font-thin py-1 px-2 text-center">カラーID</th>
                    <th class="font-thin py-1 px-2 text-center">カラーROW</th>
                    <th class="font-thin py-1 px-2 text-center">商品名</th>
                    <th class="font-thin py-1 px-2 text-center">商品カテゴリ1</th>
                    <th class="font-thin py-1 px-2 text-center">商品カテゴリ2</th>
                    <th class="font-thin py-1 px-2 text-center">ブランド</th>
                    <th class="font-thin py-1 px-2 text-center">装用期間</th>
                    <th class="font-thin py-1 px-2 text-center">入数</th>
                    <th class="font-thin py-1 px-2 text-center">メーカー</th>
                    <th class="font-thin py-1 px-2 text-center">仕入先</th>
                    <th class="font-thin py-1 px-2 text-center">検品ロット</th>
                    <th class="font-thin py-1 px-2 text-center">在庫管理</th>
                    <th class="font-thin py-1 px-2 text-center">並び順</th>
                    <th class="font-thin py-1 px-2 text-center">最終更新日時</th>
                </tr>
                <tr class="filter-row sticky top-[28px] bg-white z-10">
                    <th></th>
                    <th></th>
                    <x-filter.input type="tel" id="filter_item_code" name="filter_item_code" />
                    <x-filter.input type="tel" id="filter_item_jan_code" name="filter_item_jan_code" />
                    <x-filter.input type="tel" id="filter_color_id" name="filter_color_id" />
                    <x-filter.input type="tel" id="filter_color_row" name="filter_color_row" />
                    <x-filter.input type="text" id="filter_item_name" name="filter_item_name" />
                    <x-filter.input type="text" id="filter_item_category_1" name="filter_item_category_1" />
                    <x-filter.input type="text" id="filter_item_category_2" name="filter_item_category_2" />
                    <x-filter.input type="text" id="filter_brand" name="filter_brand" />
                    <x-filter.input type="text" id="filter_wearing_period" name="filter_wearing_period" />
                    <x-filter.input type="text" id="filter_quantity_per_box" name="filter_quantity_per_box" />
                    <x-filter.input type="text" id="filter_manufacturer" name="filter_manufacturer" />
                    <x-filter.input type="text" id="filter_supplier" name="filter_supplier" />
                    <x-filter.select-boolean id="filter_is_inspection_lot_required" name="filter_is_inspection_lot_required" label1="必要" label0="不要" />
                    <x-filter.select-boolean id="filter_is_stock_managed" name="filter_is_stock_managed" label1="有効" label0="無効" />
                    <x-filter.input type="tel" id="filter_sort_order" name="filter_sort_order" />
                    <th></th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($items as $item)
                    <tr class="text-left cursor-default whitespace-nowrap hover:bg-theme-sub group">
                        @can('warm_check')
                            <td class="py-1 px-2 border">
                                <div class="flex flex-row gap-5">
                                    <a href="{{ route('item_update.index', ['item_id' => $item->item_id]) }}" class="btn rounded bg-btn-enter text-white py-1 px-2">更新</a>
                                    <button type="button" class="btn rounded item_delete_enter bg-btn-cancel text-white py-1 px-2" data-item-id="{{ $item->item_id }}">削除</button>
                                </div>
                            </td>
                        @endcan
                        <td class="py-1 px-2 border">
                            <img class="w-10 h-10 mx-auto image_fade_in_modal_open" src="{{ asset('storage/item_images/'.$item->item_image_file_name) }}">
                        </td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $item->item_code }}
                            <x-clipboard-copy-btn :value="$item->item_code" label="商品コード" />
                        </td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $item->item_jan_code }}
                            <x-clipboard-copy-btn :value="$item->item_jan_code" label="商品JANコード" />
                        </td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $item->color_id }}
                            <x-clipboard-copy-btn :value="$item->color_id" label="カラーID" />
                        </td>
                        <td class="py-1 px-2 border text-right relative group/clipboard">
                            {{ $item->color_row }}
                            <x-clipboard-copy-btn :value="$item->color_row" label="カラーROW" />
                        </td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $item->item_name }}
                            <x-clipboard-copy-btn :value="$item->item_name" label="商品名" />
                        </td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $item->item_category_1 }}
                            <x-clipboard-copy-btn :value="$item->item_category_1" label="商品カテゴリ1" />
                        </td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $item->item_category_2 }}
                            <x-clipboard-copy-btn :value="$item->item_category_2" label="商品カテゴリ2" />
                        </td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $item->brand }}
                            <x-clipboard-copy-btn :value="$item->brand" label="ブランド" />
                        </td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $item->wearing_period }}
                            <x-clipboard-copy-btn :value="$item->wearing_period" label="装用期間" />
                        </td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $item->quantity_per_box }}
                            <x-clipboard-copy-btn :value="$item->quantity_per_box" label="入数" />
                        </td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $item->manufacturer }}
                            <x-clipboard-copy-btn :value="$item->manufacturer" label="メーカー" />
                        </td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $item->supplier }}
                            <x-clipboard-copy-btn :value="$item->supplier" label="仕入先" />
                        </td>
                        <td class="py-1 px-2 border text-center">
                            <x-list.status :value="$item->is_inspection_lot_required" label1="必要" label0="不要" />
                        </td>
                        <td class="py-1 px-2 border text-center">
                            <x-list.status :value="$item->is_stock_managed" label1="有効" label0="無効" />
                        </td>
                        <td class="py-1 px-2 border text-right">{{ number_format($item->sort_order) }}</td>
                        <td class="py-1 px-2 border">{{ CarbonImmutable::parse($item->updated_at)->isoFormat('Y年MM月DD日(ddd) HH:mm:ss') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<form method="POST" action="{{ route('item_delete.delete') }}" id="item_delete_form" class="hidden">
    @csrf
    <input type="hidden" id="item_id" name="item_id">
</form>