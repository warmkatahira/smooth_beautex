<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="input_stock_operation_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead class="sticky top-0">
                <tr class="text-left text-white bg-black whitespace-nowrap">
                    <th class="font-thin py-1 px-2 text-center">商品画像</th>
                    <th class="font-thin py-1 px-2 text-center">倉庫名</th>
                    <th class="font-thin py-1 px-2 text-center">商品コード</th>
                    <th class="font-thin py-1 px-2 text-center">商品JANコード</th>
                    <th class="font-thin py-1 px-2 text-center">商品名</th>
                    <th class="font-thin py-1 px-2 text-center">商品カテゴリ</th>
                    <th class="font-thin py-1 px-2 text-center">商品ロケーション</th>
                    <th class="font-thin py-1 px-2 text-center">全在庫数</th>
                    <th class="font-thin py-1 px-2 text-center">受注数</th>
                    <th class="font-thin py-1 px-2 text-center">有効在庫数</th>
                    <th class="font-thin py-1 px-2 text-center">数量<i class="lar la-question-circle la-lg ml-1 tippy_quantity"></i></th>
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
                                <td class="py-1 px-2 border">{{ $stock->item_code }}</td>
                                <td class="py-1 px-2 border">{{ $stock->item_jan_code }}</td>
                                <td class="py-1 px-2 border">{{ $stock->item_name }}</td>
                                <td class="py-1 px-2 border">{{ $stock->item_category }}</td>
                                <td class="py-1 px-2 border">{{ $stock->item_location }}</td>
                                <td class="py-1 px-2 border text-right">{{ number_format($stock->total_stock) }}</td>
                                <td class="py-1 px-2 border text-right">{{ number_format($stock->total_order_quantity) }}</td>
                                <td class="py-1 px-2 border text-right">{{ number_format($stock->available_stock) }}</td>
                                <td class="py-1 px-2 border text-right">
                                    <input type="tel" name="quantity[{{ $stock->base_id }}][{{ $stock->item_id }}]" class="quantity text-xs text-right py-1 w-20" value="{{ old('quantity.' . $stock->base_id . '.' . $stock->item_id) }}" autocomplete="off">
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