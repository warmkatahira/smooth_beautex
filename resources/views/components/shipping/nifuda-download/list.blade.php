<div class="disable_scrollbar flex flex-grow overflow-scroll mt-3">
        <div class="nifuda_download_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
            <table class="text-xs">
                <thead>
                    <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                        <th class="font-thin py-1 px-2">作成日時</th>
                        <th class="font-thin py-1 px-2">作成者</th>
                        <th class="font-thin py-1 px-2">出荷グループ名</th>
                        <th class="font-thin py-1 px-2">運送会社</th>
                        <th class="font-thin py-1 px-2">配送方法</th>
                        <th class="font-thin py-1 px-2">ダウンロード</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($nifudaCreateHistories as $nifuda_create_history)
                        <tr class="text-left hover:bg-theme-sub cursor-default whitespace-nowrap">
                            <td class="py-1 px-2 border">{{ CarbonImmutable::parse($nifuda_create_history->created_at)->isoFormat('Y年MM月DD日 HH時mm分ss秒') }}</td>
                            <td class="py-1 px-2 border">{{ $nifuda_create_history->full_name }}</td>
                            <td class="py-1 px-2 border">{{ $nifuda_create_history->shipping_group->shipping_group_name }}</td>
                            <td class="py-1 px-2 border">
                                <img src="{{ asset('image/'.$nifuda_create_history->shipping_method->delivery_company->company_image) }}" class="inline-block">
                            </td>
                            <td class="py-1 px-2 border">{{ $nifuda_create_history->shipping_method->shipping_method }}</td>
                            <td class="py-1 px-2 border">
                                <a href="{{ route('nifuda_download.download', ['nifuda_create_history_id' => $nifuda_create_history->nifuda_create_history_id]) }}" class="text-center text-blue-500"><i class="las la-cloud-download-alt mr-1 la-lg"></i>ダウンロード</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>