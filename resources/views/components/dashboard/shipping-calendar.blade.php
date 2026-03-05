@php
    // 日付キー一覧
    $dateKeys = array_keys($dates);
    // 月初の日付を取得
    $firstDay = CarbonImmutable::parse($dateKeys[0]);
    // 月初の日付の曜日番号を取得(0:日曜〜6:土曜)
    $firstDayOfWeek = $firstDay->dayOfWeek;
    // 日にちをカウントする変数を初期化
    $dayCounter = 0;
@endphp
<table class="bg-white w-5/12">
    <thead>
        <tr>
            <th class="font-thin border border-black w-1/7 bg-pink-100">日</th>
            <th class="font-thin border border-black w-1/7">月</th>
            <th class="font-thin border border-black w-1/7">火</th>
            <th class="font-thin border border-black w-1/7">水</th>
            <th class="font-thin border border-black w-1/7">木</th>
            <th class="font-thin border border-black w-1/7">金</th>
            <th class="font-thin border border-black w-1/7 bg-blue-100">土</th>
        </tr>
    </thead>
    <tbody>
        @foreach(range(0, 5) as $week)
            <tr class="h-24">
                @foreach(range(0, 6) as $dow)
                    @php
                        // 変数を初期化
                        $disp_date = '';
                        $count = null;
                        $bg = '';
                        // 1週目かつ、月の開始曜日より前ならなにもしない
                        if($week === 0 && $dow < $firstDayOfWeek){
                        }elseif(isset($dateKeys[$dayCounter])){
                            // 日付を取得
                            $date = CarbonImmutable::parse($dateKeys[$dayCounter])->toDateString();
                            $carbonDate = CarbonImmutable::parse($dateKeys[$dayCounter]);
                            // 日付の日にち部分だけを取得
                            // 表示用の日付
                            if($carbonDate->day === 1){
                                // 1日だけmm/ddで表示
                                $disp_date = $carbonDate->format('m/d');
                            }else{
                                // それ以外は日にちだけ
                                $disp_date = $carbonDate->day;
                            }
                            // 出荷件数を取得
                            $count = isset($shippingCount[CarbonImmutable::parse($dateKeys[$dayCounter])->toDateString()]) ? $shippingCount[CarbonImmutable::parse($dateKeys[$dayCounter])->toDateString()]->count : 0;
                            // 出荷数量を取得
                            $quantity = isset($shippingQuantity[CarbonImmutable::parse($dateKeys[$dayCounter])->toDateString()]) ? $shippingQuantity[CarbonImmutable::parse($dateKeys[$dayCounter])->toDateString()]->total_quantity : 0;
                            // 日にちをカウントアップ
                            $dayCounter++;
                        }
                        // 土曜日か日曜日なら背景色を設定
                        if($dow === 0){
                            $bg = 'bg-pink-100';
                        }elseif($dow === 6){
                            $bg = 'bg-blue-100'; 
                        }
                    @endphp
                    <td class="border border-black align-top @if(!is_null($count) && $count != 0) hover:bg-theme-sub cursor-pointer @endif {{ $bg }}">
                        @if(!empty($disp_date))
                            <a @if(!is_null($count) && $count != 0) href="{{ route('shipping_history.index', ['search_type' => 'search', 'search_shipping_date_from' => $date, 'search_shipping_date_to' => $date]) }}" @endif>
                                <div class="flex flex-col p-2">
                                    <strong class="text-base text-left">{{ $disp_date }}</strong>
                                    @if(!is_null($count) && $count != 0)
                                        <div class="flex flex-row pt-1 border-dashed border-b border-black">
                                            <p class="w-1/2">件数</p>
                                            <p class="w-1/2 text-right">{{ number_format($count) }}</p>
                                        </div>
                                        <div class="flex flex-row pt-1 border-dashed border-b border-black">
                                            <p class="w-1/2">数量</p>
                                            <p class="w-1/2 text-right">{{ number_format($quantity) }}</p>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>