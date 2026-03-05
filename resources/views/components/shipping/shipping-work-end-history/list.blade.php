<p class="text-xs mt-3 mb-1 ml-1">※直近、10日間のみ表示</p>
<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="shipping_work_end_history_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2">出荷完了実施日時</th>
                    <th class="font-thin py-1 px-2">処理件数</th>
                    <th class="font-thin py-1 px-2">処理結果</th>
                    <th class="font-thin py-1 px-2">メッセージ</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($shippingWorkEndHistories as $shipping_work_end_history)
                    <tr class="text-left hover:bg-theme-sub cursor-default whitespace-nowrap @if(!$shipping_work_end_history->is_successful) bg-pink-200 @endif">
                        <td class="py-1 px-2 border">{{ CarbonImmutable::parse($shipping_work_end_history->created_at)->isoFormat('Y年MM月DD日(ddd) HH時mm分ss秒') }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format($shipping_work_end_history->target_count) }}</td>
                        <td class="py-1 px-2 border text-center">{{ $shipping_work_end_history->statusText }}</td>
                        <td class="py-1 px-2 border">{!! nl2br(e($shipping_work_end_history->message)) !!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>