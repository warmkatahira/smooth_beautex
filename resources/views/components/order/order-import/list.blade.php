<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="order_import_history_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">取込日時</th>
                    <th class="font-thin py-1 px-2 text-center">取込ファイル名</th>
                    <th class="font-thin py-1 px-2 text-center">受注件数</th>
                    <th class="font-thin py-1 px-2 text-center">取込件数</th>
                    <th class="font-thin py-1 px-2 text-center">削除件数</th>
                    <th class="font-thin py-1 px-2 text-center">エラーファイル名</th>
                    <th class="font-thin py-1 px-2 text-center">メッセージ</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($orderImportHistories as $order_import_history)
                    <tr class="text-left cursor-default whitespace-nowrap">
                        <td class="py-1 px-2 border">{{ CarbonImmutable::parse($order_import_history->created_at)->isoFormat('Y年MM月DD日(ddd) HH:mm:ss') }}</td>
                        <td class="py-1 px-2 border">{{ $order_import_history->import_file_name }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format($order_import_history->all_order_num) }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format($order_import_history->import_order_num) }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format($order_import_history->delete_order_num) }}</td>
                        <td class="py-1 px-2 border">
                            @if(!is_null($order_import_history->error_file_name))
                            <a href="{{ route('order_import.error_download', ['filename' => $order_import_history->error_file_name]) }}" class="text-center text-blue-500"><i class="las la-cloud-download-alt mr-1 la-lg"></i>ダウンロード</a>
                        @endif
                        </td>
                        <td class="py-1 px-2 border">{{ $order_import_history->message }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>