<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="stock_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead class="sticky top-0">
                <tr class="text-center whitespace-nowrap">
                    <th class="font-thin py-1 text-sm bg-black text-white" colspan="6" scope="colgroup">商品情報</th>
                    @foreach ($bases as $base)
                        <th style="background-color: {{ $base->base_color_code }};" class="font-thin py-1 text-sm" colspan="3" scope="colgroup">{{ $base->base_name }}</th>
                    @endforeach
                </tr>
                <tr class="text-left text-white bg-black whitespace-nowrap">
                    <th class="font-thin py-1 px-2 text-center">商品画像</th>
                    <th class="font-thin py-1 px-2 text-center">商品コード</th>
                    <th class="font-thin py-1 px-2 text-center">商品JANコード</th>
                    <th class="font-thin py-1 px-2 text-center">商品名</th>
                    <th class="font-thin py-1 px-2 text-center">商品カテゴリ</th>
                    <th class="font-thin py-1 px-2 text-center">在庫管理</th>
                    @foreach($bases as $base)
                        <th class="font-thin py-1 px-2 text-center">全在庫数</th>
                        <th class="font-thin py-1 px-2 text-center">受注数</th>
                        <th class="font-thin py-1 px-2 text-center">有効在庫数</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($stocks as $stock)
                    <tr class="text-left cursor-default whitespace-nowrap hover:bg-theme-sub group">
                        <td class="py-1 px-2 border">
                            <img class="w-10 h-10 mx-auto image_fade_in_modal_open" src="{{ asset('storage/item_images/'.$stock->item_image_file_name) }}">
                        </td>
                        <td class="py-1 px-2 border">{{ $stock->item_code }}</td>
                        <td class="py-1 px-2 border">{{ $stock->item_jan_code }}</td>
                        <td class="py-1 px-2 border">{{ $stock->item_name }}</td>
                        <td class="py-1 px-2 border">{{ $stock->item_category }}</td>
                        <td class="py-1 px-2 border text-center">{{ $stock->is_stock_managed_text }}</td>
                        @foreach ($bases as $base)
                            <td style="--base-color: {{ $base->base_color_code }};" class="py-1 px-2 border text-right bg-[var(--base-color)] group-hover:bg-theme-sub">{{ number_format($stock->{'total_stock_'.$base->base_id}) }}</td>
                            <td style="--base-color: {{ $base->base_color_code }};" class="py-1 px-2 border text-right bg-[var(--base-color)] group-hover:bg-theme-sub">{{ number_format($stock->{'total_order_quantity_'.$base->base_id}) }}</td>
                            <td style="--base-color: {{ $base->base_color_code }};" class="py-1 px-2 border text-right bg-[var(--base-color)] group-hover:bg-theme-sub">{{ number_format($stock->{'available_stock_'.$base->base_id}) }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div