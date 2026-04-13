<x-app-layout>
    <div>
        <form method="POST" action="{{ route('item_qr_analysis.analysis') }}" class="flex flex-row">
            @csrf
            <div class="flex flex-row">
                <p class="pt-2.5 w-40 bg-black text-white pl-2">QRコード</p>
                <input type="text"
                    name="qr_code"
                    class="form-control font-monospace @error('qr_code') is-invalid @enderror"
                    autofocus
                    autocomplete="off">
            </div>
            <div class="flex flex-row">
                <p class="pt-2.5 w-40 bg-black text-white pl-2">JANコード</p>
                <input type="text"
                        name="jan_code"
                        class="form-control font-monospace @error('jan_code') is-invalid @enderror"
                        maxlength="13"
                        autocomplete="off">
            </div>
            <div class="flex flex-row">
                <p class="pt-2.5 w-40 bg-black text-white pl-2">LOT</p>
                <input type="text"
                        name="lot"
                        class="form-control"
                        autocomplete="off">
            </div>
            <button type="submit" class="btn text-sm bg-btn-enter text-white px-5 ml-5">解析する</button>
        </form>
    </div>
    <div class="disable_scrollbar flex flex-grow overflow-scroll mt-3">
        <div class="item_qr_analysys_history_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
            <table class="text-xs">
                <thead>
                    <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0 h-7 z-10">
                        <th class="font-thin py-1 px-2 text-center">QRコード</th>
                        <th class="font-thin py-1 px-2 text-center">JANコード</th>
                        <th class="font-thin py-1 px-2 text-center">LOT</th>
                        <th class="font-thin py-1 px-2 text-center">JANコード一致</th>
                        <th class="font-thin py-1 px-2 text-center">度数</th>
                        <th class="font-thin py-1 px-2 text-center">S-POWERコード</th>
                        <th class="font-thin py-1 px-2 text-center">S-POWERコード開始位置</th>
                        <th class="font-thin py-1 px-2 text-center">LOT一致</th>
                        <th class="font-thin py-1 px-2 text-center">LOT開始位置</th>
                        <th class="font-thin py-1 px-2 text-center">LOT桁数</th>
                        <th class="font-thin py-1 px-2 text-center">EXP開始位置</th>
                        <th class="font-thin py-1 px-2 text-center">EXP</th>
                        <th class="font-thin py-1 px-2 text-center">ユーザー</th>
                        <th class="font-thin py-1 px-2 text-center">実施日時</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($item_qr_analysis_histories as $item_qr_analysis_history)
                        <tr class="text-left cursor-default whitespace-nowrap hover:bg-theme-sub group">
                            <td class="py-1 px-2 border relative group/clipboard">
                                {{ $item_qr_analysis_history->qr_code }}
                                <x-clipboard-copy-btn :value="$item_qr_analysis_history->qr_code" label="QRコード" />
                            </td>
                            <td class="py-1 px-2 border relative group/clipboard">
                                {{ $item_qr_analysis_history->jan_code }}
                                <x-clipboard-copy-btn :value="$item_qr_analysis_history->jan_code" label="JANコード" />
                            </td>
                            <td class="py-1 px-2 border relative group/clipboard">
                                {{ $item_qr_analysis_history->lot }}
                                <x-clipboard-copy-btn :value="$item_qr_analysis_history->lot" label="LOT" />
                            </td>
                            <td class="py-1 px-2 border text-center">
                                <x-list.status :value="$item_qr_analysis_history->is_jan_code_match" label1="一致" label0="不一致" />
                            </td>
                            <td class="py-1 px-2 border relative group/clipboard">
                                {{ $item_qr_analysis_history->power }}
                                <x-clipboard-copy-btn :value="$item_qr_analysis_history->power" label="度数" />
                            </td>
                            <td class="py-1 px-2 border relative group/clipboard">
                                {{ $item_qr_analysis_history->s_power_code }}
                                <x-clipboard-copy-btn :value="$item_qr_analysis_history->s_power_code" label="S-POWERコード" />
                            </td>
                            <td class="py-1 px-2 border relative group/clipboard">
                                {{ $item_qr_analysis_history->s_power_code_start_position }}
                                <x-clipboard-copy-btn :value="$item_qr_analysis_history->s_power_code_start_position" label="S-POWERコード開始位置" />
                            </td>
                            <td class="py-1 px-2 border text-center">
                                <x-list.status :value="$item_qr_analysis_history->is_lot_match" label1="一致" label0="不一致" />
                            </td>
                            <td class="py-1 px-2 border relative group/clipboard">
                                {{ $item_qr_analysis_history->lot_start_position }}
                                <x-clipboard-copy-btn :value="$item_qr_analysis_history->lot_start_position" label="LOT開始位置" />
                            </td>
                            <td class="py-1 px-2 border relative group/clipboard">
                                {{ $item_qr_analysis_history->lot_length }}
                                <x-clipboard-copy-btn :value="$item_qr_analysis_history->lot_length" label="LOT桁数" />
                            </td>
                            <td class="py-1 px-2 border relative group/clipboard">
                                {{ $item_qr_analysis_history->exp_start_position }}
                                <x-clipboard-copy-btn :value="$item_qr_analysis_history->exp_start_position" label="EXP開始位置" />
                            </td>
                            <td class="py-1 px-2 border text-center relative group/clipboard">
                                {{ formatExp($item_qr_analysis_history->exp) }}
                                <x-clipboard-copy-btn :value="$item_qr_analysis_history->exp" label="EXP" />
                            </td>
                            <td class="py-1 px-2 border">
                                @if($item_qr_analysis_history->user)
                                    {{ $item_qr_analysis_history->user->full_name }}
                                @endif
                            </td>
                            <td class="py-1 px-2 border">{{ CarbonImmutable::parse($item_qr_analysis_history->created_at)->isoFormat('Y年MM月DD日(ddd) HH:mm:ss') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>