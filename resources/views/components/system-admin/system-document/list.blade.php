<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="common_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs block">
            <thead>
                <tr class="text-left text-white bg-gray-600 whitespace-nowrap sticky top-0">
                    @can('admin_check')
                        <th class="font-thin py-1 px-2">操作</th>
                    @endcan
                    @can('warm_check')
                        <th class="font-thin py-1 px-2">社内資料</th>
                    @endcan
                    @can('admin_check')
                        <th class="font-thin py-1 px-2">並び順</th>
                    @endcan
                    <th class="font-thin py-1 px-2">ファイル名</th>
                    <th class="font-thin py-1 px-2">更新日時</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($systemDocuments as $system_document)
                    @if($system_document->is_internal == 0 || $system_document->is_internal == 1 && auth()->user()->can('warm_check'))
                        <tr class="text-left hover:bg-theme-sub cursor-default whitespace-nowrap">
                            @can('admin_check')
                                <td class="py-1 px-2 border">
                                    <div class="flex">
                                        <form method="post" action="{{ route('system_document_delete.delete') }}" id="{{ 'system_document_delete_form_'.$system_document->system_document_id }}" class="m-0">
                                            @csrf
                                            <input type="hidden" name="system_document_id" value="{{ $system_document->system_document_id }}">
                                            <button type="button" class="system_document_delete btn bg-btn-cancel text-white py-1 px-2" value="{{ $system_document->system_document_id }}">削除</button>
                                        </form>
                                    </div>
                                </td>
                            @endcan
                            @can('warm_check')
                                <td class="py-1 px-2 border text-center">@if($system_document->is_internal) ○ @endif</td>
                            @endcan
                            @can('admin_check')
                                <td class="py-1 px-2 border text-right">{{ $system_document->sort_order }}</td>
                            @endcan
                            <td class="py-1 px-2 border"><a href="{{ asset('storage/system_document/'.$system_document->file_name) }}" class="text-blue-500 underline" target="_blank">{{ $system_document->file_name }}</a></td>
                            <td class="py-1 px-2 border">{{ CarbonImmutable::parse($system_document->updated_at)->isoFormat('YYYY年MM月DD日(ddd) HH:mm:ss') }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>