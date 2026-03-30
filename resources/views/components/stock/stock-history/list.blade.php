<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="stock_history_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table id="filter_table" class="text-xs" data-search-url="/stock_history" data-scroll-target=".stock_history_list">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0 h-7 z-10">
                    <th class="font-thin py-1 px-2 text-center">履歴日</th>
                    <th class="font-thin py-1 px-2 text-center">履歴時間</th>
                    <th class="font-thin py-1 px-2 text-center">区分</th>
                    <th class="font-thin py-1 px-2 text-center">実行ユーザー</th>
                    <th class="font-thin py-1 px-2 text-center">倉庫名</th>
                    <th class="font-thin py-1 px-2 text-center">商品画像</th>
                    <th class="font-thin py-1 px-2 text-center">商品コード</th>
                    <th class="font-thin py-1 px-2 text-center">商品JANコード</th>
                    <th class="font-thin py-1 px-2 text-center">商品名</th>
                    <th class="font-thin py-1 px-2 text-center">商品カテゴリ1</th>
                    <th class="font-thin py-1 px-2 text-center">商品カテゴリ2</th>
                    <th class="font-thin py-1 px-2 text-center">LOT</th>
                    <th class="font-thin py-1 px-2 text-center">EXP</th>
                    <th class="font-thin py-1 px-2 text-center">数量</th>
                    <th class="font-thin py-1 px-2 text-center">コメント</th>
                </tr>
                <tr class="filter-row sticky top-[28px] bg-white z-10">
                    <x-filter.date-period type="date" fromId="filter_history_date_from" fromName="filter_history_date_from" toId="filter_history_date_to" toName="filter_history_date_to" />
                    <x-filter.input type="tel" id="filter_history_time" name="filter_history_time" />
                    <x-filter.select id="filter_stock_history_category_id" name="filter_stock_history_category_id" :selectItems="$stockHistoryCategories" optionValue="stock_history_category_id" optionText="stock_history_category_name" />
                    <x-filter.select id="filter_user_no" name="filter_user_no" :selectItems="$users" optionValue="user_no" optionText="full_name" />
                    <x-filter.select id="filter_base_id" name="filter_base_id" :selectItems="$bases" optionValue="base_id" optionText="base_name" />
                    <th></th>
                    <x-filter.input type="tel" id="filter_item_code" name="filter_item_code" />
                    <x-filter.input type="tel" id="filter_item_jan_code" name="filter_item_jan_code" />
                    <x-filter.input type="text" id="filter_item_name" name="filter_item_name" />
                    <x-filter.input type="text" id="filter_item_category_1" name="filter_item_category_1" />
                    <x-filter.input type="text" id="filter_item_category_2" name="filter_item_category_2" />
                    <x-filter.input type="tel" id="filter_lot" name="filter_lot" />
                    <x-filter.input type="tel" id="filter_exp" name="filter_exp" placeholder="YYYYMM形式" />
                    <x-filter.input type="tel" id="filter_quantity" name="filter_quantity" />
                    <x-filter.input type="text" id="filter_comment" name="filter_comment" />
                    <th></th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($stockHistories as $stock_history)
                    <tr class="text-left cursor-default whitespace-nowrap hover:bg-theme-sub group">
                        <td class="py-1 px-2 border text-center">{{ CarbonImmutable::parse($stock_history->updated_at)->isoFormat('Y年MM月DD日(ddd)') }}</td>
                        <td class="py-1 px-2 border text-center">{{ CarbonImmutable::parse($stock_history->updated_at)->isoFormat('HH:mm:ss') }}</td>
                        <td class="py-1 px-2 border text-center">{{ $stock_history->stock_history_category_name }}</td>
                        <td class="py-1 px-2 border">
                            @if($stock_history->user)
                                <img class="profile_image_normal" src="{{ asset('storage/profile_images/'.$stock_history->user->profile_image_file_name) }}">
                                {{ $stock_history->user->full_name }}
                            @endif
                        </td>
                        <td class="py-1 px-2 border text-center">{{ $stock_history->base_name }}</td>
                        <td class="py-1 px-2 border">
                            <img class="w-10 h-10 mx-auto" src="{{ asset('storage/item_images/'.$stock_history->item_image_file_name) }}">
                        </td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $stock_history->item_code }}
                            <x-clipboard-copy-btn :value="$stock_history->item_code" label="商品コード" />
                        </td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $stock_history->item_jan_code }}
                            <x-clipboard-copy-btn :value="$stock_history->item_jan_code" label="商品JANコード" />
                        </td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $stock_history->item_name }}
                            <x-clipboard-copy-btn :value="$stock_history->item_name" label="商品名" />
                        </td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $stock_history->item_category_1 }}
                            <x-clipboard-copy-btn :value="$stock_history->item_category_1" label="商品カテゴリ1" />
                        </td>
                        <td class="py-1 px-2 border relative group/clipboard">
                            {{ $stock_history->item_category_2 }}
                            <x-clipboard-copy-btn :value="$stock_history->item_category_2" label="商品カテゴリ2" />
                        </td>
                        <td class="py-1 px-2 border text-center relative group/clipboard">
                            {{ $stock_history->lot }}
                            <x-clipboard-copy-btn :value="$stock_history->lot" label="LOT" />
                        </td>
                        <td class="py-1 px-2 border text-center relative group/clipboard">
                            {{ formatExp($stock_history->exp) }}
                            <x-clipboard-copy-btn :value="$stock_history->exp" label="EXP" />
                        </td>
                        <td class="py-1 px-2 border text-right">{{ number_format($stock_history->quantity) }}</td>
                        <td class="py-1 px-2 border">{{ Str::limit($stock_history->comment, 20) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div