<table class="text-xs">
    <thead>
        <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
            <th class="font-thin py-1 px-2 text-center">出荷倉庫</th>
            <th class="font-thin py-1 px-2 text-center">出荷予定日</th>
            <th class="font-thin py-1 px-2 text-center">出荷完了対象件数<i class="tippy_shipping_work_end_target_count las la-info-circle la-lg ml-1"></i></th>
            <th class="font-thin py-1 px-2 text-center">出荷完了対象外件数<i class="tippy_not_shipping_work_end_target_count las la-info-circle la-lg ml-1"></i></th>
        </tr>
    </thead>
    <tbody class="bg-white">
        @foreach($shippingWorkEndInfoArr as $base_name => $dates)
            @foreach($dates as $estimated_shipping_date => $value)
                <tr class="text-left cursor-default whitespace-nowrap">
                    @if($loop->first)
                        <td class="py-1 px-2 border" rowspan="{{ count($dates) }}">{{ $base_name }}</td>
                    @endif
                    <td class="py-1 px-2 border text-center">
                        @php
                            $date = CarbonImmutable::parse($estimated_shipping_date);
                            $diff = (int) CarbonImmutable::today()->diffInDays($date, false);
                            $diff_label = match(true) {
                                $diff === 0  => '今日',
                                $diff === 1  => '明日',
                                $diff === -1 => '昨日',
                                $diff > 1    => $diff.'日後',
                                $diff < -1   => abs($diff).'日前',
                            };
                        @endphp
                        {{ $date->isoFormat('Y年MM月DD日(ddd)') }}<span class="{{ $diff <= 0 ? 'bg-red-200 font-semibold' : 'text-gray-700' }} rounded py-1 px-3 ml-1">{{ $diff_label }}</span>
                    </td>
                    <td class="py-1 px-2 border text-right">{{ number_format($value[1]) }}</td>
                    <td class="py-1 px-2 border text-right not_shipping_work_end_count">{{ number_format($value[0]) }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>