<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="stock_history_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead class="sticky top-0">
                <tr class="text-left text-white bg-black whitespace-nowrap">
                    <th class="font-thin py-1 px-2 text-center">商品画像</th>
                    <th class="font-thin py-1 px-2 text-center">日時</th>
                    <th class="font-thin py-1 px-2 text-center">区分</th>
                    <th class="font-thin py-1 px-2 text-center">実行ユーザー</th>
                    <th class="font-thin py-1 px-2 text-center">倉庫名</th>
                    <th class="font-thin py-1 px-2 text-center">商品コード</th>
                    <th class="font-thin py-1 px-2 text-center">商品JANコード</th>
                    <th class="font-thin py-1 px-2 text-center">商品名</th>
                    <th class="font-thin py-1 px-2 text-center">商品カテゴリ</th>
                    <th class="font-thin py-1 px-2 text-center">数量</th>
                    <th class="font-thin py-1 px-2 text-center">コメント</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($stockHistories as $stock_history)
                    <tr class="text-left cursor-default whitespace-nowrap hover:bg-theme-sub group">
                        <td class="py-1 px-2 border">
                            <img class="w-10 h-10 mx-auto" src="{{ asset('storage/item_images/'.$stock_history->item_image_file_name) }}">
                        </td>
                        <td class="py-1 px-2 border">{{ CarbonImmutable::parse($stock_history->updated_at)->isoFormat('Y年MM月DD日(ddd) HH:mm:ss') }}</td>
                        <td class="py-1 px-2 border text-center">{{ $stock_history->stock_history_category_name }}</td>
                        <td class="py-1 px-2 border">
                            @if($stock_history->user)
                                <img class="profile_image_normal" src="{{ asset('storage/profile_images/'.$stock_history->user->profile_image_file_name) }}">
                                {{ $stock_history->user->full_name }}
                            @endif
                        </td>
                        <td class="py-1 px-2 border">{{ $stock_history->base_name }}</td>
                        <td class="py-1 px-2 border">{{ $stock_history->item_code }}</td>
                        <td class="py-1 px-2 border">{{ $stock_history->item_jan_code }}</td>
                        <td class="py-1 px-2 border">{{ $stock_history->item_name }}</td>
                        <td class="py-1 px-2 border">{{ $stock_history->item_category }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format($stock_history->quantity) }}</td>
                        <td class="py-1 px-2 border">{{ Str::limit($stock_history->comment, 20) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div