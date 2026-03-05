<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="operation_log_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">操作日</th>
                    <th class="font-thin py-1 px-2 text-center">操作時間</th>
                    <th class="font-thin py-1 px-2 text-center">ユーザー名</th>
                    <th class="font-thin py-1 px-2 text-center">IPアドレス</th>
                    <th class="font-thin py-1 px-2 text-center">メソッド</th>
                    <th class="font-thin py-1 px-2 text-center">パス</th>
                    <th class="font-thin py-1 px-2 text-center">パラメータ</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($operationLogs as $operation_log)
                    <tr class="text-left hover:bg-theme-sub cursor-default whitespace-nowrap">
                        <td class="py-1 px-2 border">{{ CarbonImmutable::parse($operation_log['operation_date'])->isoFormat('YYYY年MM月DD日(ddd)') }}</td>
                        <td class="py-1 px-2 border">{{ CarbonImmutable::parse($operation_log['operation_time'])->isoFormat('HH時mm分ss秒') }}</td>
                        <td class="py-1 px-2 border">{{ $operation_log['user_name'] }}</td>
                        <td class="py-1 px-2 border">{{ $operation_log['ip_address'] }}</td>
                        <td class="py-1 px-2 border">{{ $operation_log['method'] }}</td>
                        <td class="py-1 px-2 border">{{ $operation_log['path'] }}</td>
                        <td class="py-1 px-2 border">{{ Str::limit($operation_log['param'], 100) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>