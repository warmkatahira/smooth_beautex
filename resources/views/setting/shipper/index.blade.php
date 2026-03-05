<x-app-layout>
    <table class="text-xs mt-3">
        <thead>
            <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                <th class="font-thin py-1 px-2 text-center">操作</th>
                <th class="font-thin py-1 px-2 text-center">荷送人会社名</th>
                <th class="font-thin py-1 px-2 text-center">荷送人名</th>
                <th class="font-thin py-1 px-2 text-center">荷送人郵便番号</th>
                <th class="font-thin py-1 px-2 text-center">荷送人住所</th>
                <th class="font-thin py-1 px-2 text-center">荷送人電話番号</th>
                <th class="font-thin py-1 px-2 text-center">荷送人メールアドレス</th>
                <th class="font-thin py-1 px-2 text-center">荷送人インボイス番号</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            @foreach($shippers as $shipper)
                <tr class="text-left cursor-default whitespace-nowrap">
                    <td class="py-1 px-2 border">
                        <div class="flex flex-row gap-5">
                            <a href="{{ route('shipper_update.index', ['shipper_id' => $shipper->shipper_id]) }}" class="btn bg-btn-enter text-white py-1 px-2">更新</a>
                        </div>
                    </td>
                    <td class="py-1 px-2 border">{{ $shipper->shipper_company_name }}</td>
                    <td class="py-1 px-2 border">{{ $shipper->shipper_name }}</td>
                    <td class="py-1 px-2 border">{{ $shipper->shipper_zip_code }}</td>
                    <td class="py-1 px-2 border">{{ $shipper->shipper_address }}</td>
                    <td class="py-1 px-2 border">{{ $shipper->shipper_tel }}</td>
                    <td class="py-1 px-2 border">{{ $shipper->shipper_email }}</td>
                    <td class="py-1 px-2 border">{{ $shipper->shipper_invoice_no }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-app-layout>