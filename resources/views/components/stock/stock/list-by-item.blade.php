<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="stock_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table id="filter_table" class="text-xs" data-search-url="/stock/index_by_item" data-scroll-target=".stock_list">
            <thead class="sticky top-0 z-10">
                <tr class="text-center whitespace-nowrap">
                    <th class="font-thin py-1 text-sm bg-black text-white" colspan="7" scope="colgroup">商品情報</th>
                    @foreach ($bases as $base)
                        <th style="background-color: {{ $base->base_color_code }};" class="font-thin py-1 text-sm" colspan="2" scope="colgroup">{{ $base->base_name }}</th>
                    @endforeach
                </tr>
                <tr class="text-left text-white bg-black whitespace-nowrap h-7">
                    <th class="font-thin py-1 px-2 text-center">商品画像</th>
                    <th class="font-thin py-1 px-2 text-center">商品コード</th>
                    <th class="font-thin py-1 px-2 text-center">商品JANコード</th>
                    <th class="font-thin py-1 px-2 text-center">商品名</th>
                    <th class="font-thin py-1 px-2 text-center">商品カテゴリ1</th>
                    <th class="font-thin py-1 px-2 text-center">商品カテゴリ2</th>
                    <th class="font-thin py-1 px-2 text-center">在庫管理</th>
                    @foreach($bases as $base)
                        <th class="font-thin py-1 px-2 text-center">在庫数</th>
                        <th class="font-thin py-1 px-2 text-center">受注数</th>
                    @endforeach
                </tr>
                <tr class="filter-row bg-white">
                    <th></th>
                    <x-filter.input type="tel" id="filter_item_code" name="filter_item_code" />
                    <x-filter.input type="tel" id="filter_item_jan_code" name="filter_item_jan_code" />
                    <x-filter.input type="text" id="filter_item_name" name="filter_item_name" />
                    <x-filter.input type="text" id="filter_item_category_1" name="filter_item_category_1" />
                    <x-filter.input type="text" id="filter_item_category_2" name="filter_item_category_2" />
                    <x-filter.select-boolean id="filter_is_stock_managed" name="filter_is_stock_managed" label1="有効" label0="無効" />
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($stocks as $stock)
                    <tr class="text-left cursor-default whitespace-nowrap hover:bg-theme-sub group">
                        <td class="py-1 px-2 border">
                            <img class="w-10 h-10 mx-auto image_fade_in_modal_open" src="{{ asset('storage/item_images/'.$stock->item_image_file_name) }}">
                        </td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $stock->item_code }}
                            <x-clipboard-copy-btn :value="$stock->item_code" label="商品コード" />
                        </td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $stock->item_jan_code }}
                            <x-clipboard-copy-btn :value="$stock->item_jan_code" label="商品JANコード" />
                        </td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $stock->item_name }}
                            <x-clipboard-copy-btn :value="$stock->item_name" label="商品名" />
                        </td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $stock->item_category_1 }}
                            <x-clipboard-copy-btn :value="$stock->item_category_1" label="商品カテゴリ1" />
                        </td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $stock->item_category_2 }}
                            <x-clipboard-copy-btn :value="$stock->item_category_2" label="商品カテゴリ2" />
                        </td>
                        <td class="py-1 px-2 border text-center">
                            <x-list.status :value="$stock->is_stock_managed" label1="有効" label0="無効" />
                        </td>
                        @foreach ($bases as $base)
                            <td style="--base-color: {{ $base->base_color_code }};" class="py-1 px-2 border text-right bg-[var(--base-color)] group-hover:bg-theme-sub">{{ number_format($stock->{'total_stock_'.$base->base_id}) }}</td>
                            <td style="--base-color: {{ $base->base_color_code }};" class="py-1 px-2 border text-right bg-[var(--base-color)] group-hover:bg-theme-sub">{{ number_format($stock->{'total_shipping_quantity_'.$base->base_id}) }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div