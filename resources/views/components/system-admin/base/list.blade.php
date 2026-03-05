<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="base_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">操作</th>
                    <th class="font-thin py-1 px-2 text-center">倉庫ID</th>
                    <th class="font-thin py-1 px-2 text-center">倉庫名</th>
                    <th class="font-thin py-1 px-2 text-center">倉庫カラー</th>
                    <th class="font-thin py-1 px-2 text-center">ミエルカスタマーコード</th>
                    <th class="font-thin py-1 px-2 text-center">並び順</th>
                    <th class="font-thin py-1 px-2 text-center">最終更新日時</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($bases as $base)
                    <tr class="text-left cursor-default whitespace-nowrap">
                        <td class="py-1 px-2 border">
                            <div class="flex flex-row gap-5">
                                <a href="{{ route('base_update.index', ['base_id' => $base->base_id]) }}" class="btn bg-btn-enter text-white py-1 px-2">更新</a>
                            </div>
                        </td>
                        <td class="py-1 px-2 border">{{ $base->base_id }}</td>
                        <td class="py-1 px-2 border">{{ $base->base_name }}</td>
                        <td class="py-1 px-2 border">
                            <div class="w-16 h-6 rounded border border-black" style="background-color: {{ $base->base_color_code }};"></div>
                        </td>
                        <td class="py-1 px-2 border">{{ $base->mieru_customer_code }}</td>
                        <td class="py-1 px-2 border text-right">{{ $base->sort_order }}</td>
                        <td class="py-1 px-2 border">{{ CarbonImmutable::parse($base->updated_at)->isoFormat('Y年MM月DD日(ddd) HH:mm:ss') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>