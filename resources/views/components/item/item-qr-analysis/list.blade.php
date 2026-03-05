<div class="disable_scrollbar flex flex-grow overflow-scroll mt-5">
    <div class="item_qr_analysis_history_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">解析日時</th>
                    <th class="font-thin py-1 px-2 text-center">QRコード</th>
                    <th class="font-thin py-1 px-2 text-center">JANバーコード</th>
                    <th class="font-thin py-1 px-2 text-center">LOTバーコード</th>
                    <th class="font-thin py-1 px-2 text-center">度数</th>
                    <th class="font-thin py-1 px-2 text-center">商品区分</th>
                    <th class="font-thin py-1 px-2 text-center">LOT開始位置</th>
                    <th class="font-thin py-1 px-2 text-center">LOT桁数</th>
                    <th class="font-thin py-1 px-2 text-center">S-POWERコード</th>
                    <th class="font-thin py-1 px-2 text-center">S-POWERコード開始位置</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($itemQrAnalysisHistories as $item_qr_analysis_history)
                    <tr class="text-left cursor-default whitespace-nowrap">
                        <td class="py-1 px-2 border">{{ CarbonImmutable::parse($item_qr_analysis_history->created_at)->isoFormat('Y年MM月DD日(ddd) HH:mm:ss') }}</td>
                        <td class="py-1 px-2 border">{{ $item_qr_analysis_history->doari_qr }}</td>
                        <td class="py-1 px-2 border">{{ $item_qr_analysis_history->doari_jan }}</td>
                        <td class="py-1 px-2 border">{{ $item_qr_analysis_history->doari_lot }}</td>
                        <td class="py-1 px-2 border text-right">{{ $item_qr_analysis_history->doari_power }}</td>
                        <td class="py-1 px-2 border text-center">{{ $item_qr_analysis_history->item_type }}</td>
                        <td class="py-1 px-2 border text-right">{{ $item_qr_analysis_history->lot_start_position }}</td>
                        <td class="py-1 px-2 border text-right">{{ is_null($item_qr_analysis_history->doari_lot) ? null : strlen($item_qr_analysis_history->doari_lot) }}</td>
                        <td class="py-1 px-2 border text-right">{{ $item_qr_analysis_history->s_power_code }}</td>
                        <td class="py-1 px-2 border text-right">{{ $item_qr_analysis_history->s_power_code_start_position }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>