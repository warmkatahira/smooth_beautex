<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="input_stock_operation_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table id="filter_table" class="text-xs" data-search-url="/input_stock_operation" data-scroll-target=".input_stock_operation_list">
            <thead class="sticky top-0">
                <tr class="text-left text-white bg-black whitespace-nowrap">
                    <th class="font-thin py-1 px-2 text-center">商品画像</th>
                    <th class="font-thin py-1 px-2 text-center">倉庫名</th>
                    <th class="font-thin py-1 px-2 text-center">商品コード</th>
                    <th class="font-thin py-1 px-2 text-center">商品JANコード</th>
                    <th class="font-thin py-1 px-2 text-center">商品名</th>
                    <th class="font-thin py-1 px-2 text-center">商品カテゴリ1</th>
                    <th class="font-thin py-1 px-2 text-center">商品カテゴリ2</th>
                    <th class="font-thin py-1 px-2 text-center">商品ロケーション</th>
                    <th class="font-thin py-1 px-2 text-center">在庫管理</th>
                    <th class="font-thin py-1 px-2 text-center">LOT</th>
                    <th class="font-thin py-1 px-2 text-center">EXP</th>
                    <th class="font-thin py-1 px-2 text-center">在庫数</th>
                    <th class="font-thin py-1 px-2 text-center">数量<i class="lar la-question-circle la-lg ml-1 tippy_quantity"></i></th>
                </tr>
                <tr class="filter-row sticky top-0 bg-white z-10 h-8">
                    <th></th>
                    <x-filter.select id="filter_base_id" name="filter_base_id" :selectItems="$bases" optionValue="base_id" optionText="base_name" />
                    <x-filter.input type="tel" id="filter_item_code" name="filter_item_code" />
                    <x-filter.input type="tel" id="filter_item_jan_code" name="filter_item_jan_code" />
                    <x-filter.input type="text" id="filter_item_name" name="filter_item_name" />
                    <x-filter.input type="text" id="filter_item_category_1" name="filter_item_category_1" />
                    <x-filter.input type="text" id="filter_item_category_2" name="filter_item_category_2" />
                    <x-filter.input type="text" id="filter_item_location" name="filter_item_location" />
                    <x-filter.select-boolean id="filter_is_stock_managed" name="filter_is_stock_managed" label1="有効" label0="無効" />
                    <x-filter.input type="tel" id="filter_lot" name="filter_lot" />
                    <x-filter.input type="tel" id="filter_exp" name="filter_exp" placeholder="YYYYMM形式" />
                </tr>
            </thead>
            <tbody class="bg-white">
                <form method="POST" action="{{ route('input_stock_operation_enter.enter') }}" id="input_stock_operation_enter_form" class="m-0">
                    @csrf
                        @foreach($stocks as $stock)
                            <tr style="--base-color: {{ $stock->base_color_code }};"  class="bg-[var(--base-color)] text-left cursor-default whitespace-nowrap hover:bg-theme-sub">
                                <td class="py-1 px-2 border">
                                    <img class="w-10 h-10 mx-auto image_fade_in_modal_open" src="{{ asset('storage/item_images/'.$stock->item_image_file_name) }}">
                                </td>
                                <td class="py-1 px-2 border">{{ $stock->base_name }}</td>
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
                                <td class="py-1 px-2 border relative group/clipboard">
                                    {{ $stock->item_location }}
                                    <x-clipboard-copy-btn :value="$stock->item_location" label="商品ロケーション" />
                                </td>
                                <td class="py-1 px-2 border text-center">
                                    <x-list.status :value="$stock->is_stock_managed" label1="有効" label0="無効" />
                                </td>
                                <td class="py-1 px-2 border text-center relative group/clipboard">
                                    {{ $stock->lot }}
                                    <x-clipboard-copy-btn :value="$stock->lot" label="LOT" />
                                </td>
                                <td class="py-1 px-2 border text-center relative group/clipboard">
                                    {{ formatExp($stock->exp) }}
                                    <x-clipboard-copy-btn :value="$stock->exp" label="EXP" />
                                </td>
                                <td class="py-1 px-2 border text-right">{{ number_format($stock->total_stock) }}</td>
                                <td class="py-1 px-2 border text-right">
                                    <input type="tel" name="quantity[{{ $stock->stock_id }}]" class="quantity text-xs text-right py-1 w-20" value="{{ old('quantity.' . $stock->stock_id) }}" autocomplete="off">
                                </td>
                            </tr>
                        @endforeach
                    </form>
                <input type="hidden" id="comment" name="comment" value="" form="input_stock_operation_enter_form">
                <input type="hidden" id="proc_type" name="proc_type" value="" form="input_stock_operation_enter_form">
            </tbody>
        </table>
    </div>
</div