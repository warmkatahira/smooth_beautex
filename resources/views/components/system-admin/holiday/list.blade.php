<div class="grid grid-cols-12 gap-5">
    @foreach($holidaysByYear as $year => $holidaysOfYear)
        <div class="col-span-4 border rounded">
            <button type="button" class="year_btn btn bg-gray-500 text-white w-full text-left px-4 py-2 flex justify-between items-center accordion-button">
                <span>{{ $year }}年</span>
                <span class="accordion-icon">+</span>
            </button>
            <div class="accordion-content hidden w-full">
                <div class="overflow-x-auto">
                    <table class="text-xs border border-gray-400 w-full">
                        <thead>
                            <tr class="bg-black text-white text-left whitespace-nowrap">
                                <th class="font-thin py-1 px-2 text-center">日付</th>
                                <th class="font-thin py-1 px-2 text-center">名称</th>
                                <th class="font-thin py-1 px-2 text-center">国民の祝日</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach($holidaysOfYear as $holiday)
                                <tr class="text-left cursor-default whitespace-nowrap hover:bg-theme-sub">
                                    <td class="py-1 px-2 border">{{ CarbonImmutable::parse($holiday->date)->isoFormat('Y年MM月DD日(ddd)') }}</td>
                                    <td class="py-1 px-2 border">{{ $holiday->name }}</td>
                                    <td class="py-1 px-2 border text-center">{{ $holiday->is_national_text }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach
</div>