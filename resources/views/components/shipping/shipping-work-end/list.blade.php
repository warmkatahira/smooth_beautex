<table class="text-xs">
    <thead>
        <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
            <th class="font-thin py-1 px-2 text-center">出荷倉庫</th>
            <th class="font-thin py-1 px-2 text-center">出荷完了対象件数<i class="tippy_shipping_work_end_target_count las la-info-circle la-lg ml-1"></i></th>
            <th class="font-thin py-1 px-2 text-center">出荷完了対象外件数<i class="tippy_not_shipping_work_end_target_count las la-info-circle la-lg ml-1"></i></th>
        </tr>
    </thead>
    <tbody class="bg-white">
        @foreach($shippingWorkEndInfoArr as $key => $value)
            <tr class="text-left cursor-default whitespace-nowrap">
                <td class="py-1 px-2 border">{{ $key }}</td>
                <td class="py-1 px-2 border text-right">{{ number_format($value[1]) }}</td>
                <td class="py-1 px-2 border text-right not_shipping_work_end_count">{{ number_format($value[0]) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>