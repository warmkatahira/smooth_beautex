<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="auto_process_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">操作</th>
                    <th class="font-thin py-1 px-2 text-center">自動処理名</th>
                    <th class="font-thin py-1 px-2 text-center">アクション区分</th>
                    <th class="font-thin py-1 px-2 text-center">アクション値</th>
                    <th class="font-thin py-1 px-2 text-center">条件一致区分</th>
                    <th class="font-thin py-1 px-2 text-center">設定条件数</th>
                    <th class="font-thin py-1 px-2 text-center">有効/無効</th>
                    <th class="font-thin py-1 px-2 text-center">実行順</th>
                    <th class="font-thin py-1 px-2 text-center">更新日時</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($autoProcesses as $auto_process)
                    <tr class="text-left whitespace-nowrap @if(!$auto_process->is_active) bg-gray-300 @endif">
                        <td class="py-1 px-2 border">
                            <div class="flex flex-row gap-5">
                                <a href="{{ route('auto_process_update.index', ['auto_process_id' => $auto_process->auto_process_id]) }}" class="btn bg-btn-enter text-white py-1 px-2">更新</a>
                                <a href="{{ route('auto_process_condition_update.index', ['auto_process_id' => $auto_process->auto_process_id]) }}" class="btn bg-btn-enter text-white py-1 px-2">条件設定</a>
                                <button type="button" class="btn auto_process_delete_enter bg-btn-cancel text-white py-1 px-2" data-auto-process-id="{{ $auto_process->auto_process_id }}">削除</button>
                            </div>
                        </td>
                        <td class="py-1 px-2 border auto_process_name">{{ $auto_process->auto_process_name }}</td>
                        <td class="py-1 px-2 border">{{ AutoProcessEnum::getActionTypeJP($auto_process->action_type) }}</td>
                        <td class="py-1 px-2 border">{{ $auto_process->action_value_text }}</td>
                        <td class="py-1 px-2 border">{{ AutoProcessEnum::getConditionMatchTypeJP($auto_process->condition_match_type) }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format($auto_process->auto_process_conditions->count()) }}</td>
                        <td class="py-1 px-2 border text-center">{{ $auto_process->is_active_text }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format($auto_process->sort_order) }}</td>
                        <td class="py-1 px-2 border">{{ CarbonImmutable::parse($auto_process->updated_at)->isoFormat('Y年MM月DD日(ddd) HH:mm:ss') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<form method="POST" action="{{ route('auto_process_delete.delete') }}" id="auto_process_delete_form" class="hidden">
    @csrf
    <input type="hidden" id="auto_process_id" name="auto_process_id">
</form>