<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="item_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    @can('warm_check')
                        <th class="font-thin py-1 px-2 text-center">操作</th>
                    @endcan
                    <th class="font-thin py-1 px-2 text-center">商品画像</th>
                    <th class="font-thin py-1 px-2 text-center">商品コード</th>
                    <th class="font-thin py-1 px-2 text-center">商品JANコード</th>
                    <th class="font-thin py-1 px-2 text-center">商品名</th>
                    <th class="font-thin py-1 px-2 text-center">商品カテゴリ</th>
                    <th class="font-thin py-1 px-2 text-center">在庫管理</th>
                    <th class="font-thin py-1 px-2 text-center">並び順</th>
                    <th class="font-thin py-1 px-2 text-center">最終更新日時</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($items as $item)
                    <tr class="text-left cursor-default whitespace-nowrap">
                        @can('warm_check')
                            <td class="py-1 px-2 border">
                                <div class="flex flex-row gap-5">
                                    <a href="{{ route('item_update.index', ['item_id' => $item->item_id]) }}" class="btn bg-btn-enter text-white py-1 px-2">更新</a>
                                    <button type="button" class="btn item_delete_enter bg-btn-cancel text-white py-1 px-2" data-item-id="{{ $item->item_id }}">削除</button>
                                </div>
                            </td>
                        @endcan
                        <td class="py-1 px-2 border">
                            <img class="w-10 h-10 mx-auto image_fade_in_modal_open" src="{{ asset('storage/item_images/'.$item->item_image_file_name) }}">
                        </td>
                        <td class="py-1 px-2 border">{{ $item->item_code }}</td>
                        <td class="py-1 px-2 border">{{ $item->item_jan_code }}</td>
                        <td class="py-1 px-2 border">{{ $item->item_name }}</td>
                        <td class="py-1 px-2 border">{{ $item->item_category }}</td>
                        <td class="py-1 px-2 border text-center">{{ $item->is_stock_managed_text }}</td>
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