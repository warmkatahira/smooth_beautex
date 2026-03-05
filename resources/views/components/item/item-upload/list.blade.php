<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="item_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">日時</th>
                    <th class="font-thin py-1 px-2 text-center">ユーザー</th>
                    <th class="font-thin py-1 px-2 text-center">対象</th>
                    <th class="font-thin py-1 px-2 text-center">タイプ</th>
                    <th class="font-thin py-1 px-2 text-center">ファイル名</th>
                    <th class="font-thin py-1 px-2 text-center">ステータス</th>
                    <th class="font-thin py-1 px-2 text-center">エラーファイル名</th>
                    <th class="font-thin py-1 px-2 text-center">メッセージ</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($itemUploadHistories as $item_upload_history)
                    <tr class="text-left cursor-default whitespace-nowrap">
                        <td class="py-1 px-2 border">{{ CarbonImmutable::parse($item_upload_history->updated_at)->isoFormat('Y年MM月DD日(ddd) HH:mm:ss') }}</td>
                        <td class="py-1 px-2 border">{{ $item_upload_history->user->full_name }}</td>
                        <td class="py-1 px-2 border text-center">{{ $item_upload_history->upload_target_text }}</td>
                        <td class="py-1 px-2 border text-center">{{ $item_upload_history->upload_type_text }}</td>
                        <td class="py-1 px-2 border">{{ $item_upload_history->upload_file_name }}</td>
                        <td class="py-1 px-2 border text-center">{{ $item_upload_history->status }}</td>
                        <td class="py-1 px-2 border">
                            @if(!is_null($item_upload_history->error_file_name))
                                <a href="{{ route('item_upload.error_download', ['filename' => $item_upload_history->error_file_name]) }}" class="text-center text-blue-500"><i class="las la-cloud-download-alt mr-1 la-lg"></i>ダウンロード</a>
                            @endif
                        </td>
                        <td class="py-1 px-2 border">{{ $item_upload_history->message }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>