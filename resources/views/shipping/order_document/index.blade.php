<x-app-layout>
    <x-page-back :url="session('back_url_1')" />
    <table class="text-xs block mt-5">
        <thead>
            <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                <th class="font-thin py-1 px-2">運送会社</th>
                <th class="font-thin py-1 px-2">配送方法</th>
                <th class="font-thin py-1 px-2">開始件数</th>
                <th class="font-thin py-1 px-2">終了件数</th>
                <th class="font-thin py-1 px-2 text-center">操作</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            @foreach($ranges as $range)
                <tr class="text-left hover:bg-theme-sub cursor-default whitespace-nowrap">
                    <td class="py-1 px-2 border text-right"><img src="{{ asset('image/'.$shipping_method->delivery_company->company_image) }}" class="inline-block"></td>
                    <td class="py-1 px-2 border text-right">{{ $shipping_method->shipping_method }}</td>
                    <td class="py-1 px-2 border text-right">{{ number_format($range['start']) }}</td>
                    <td class="py-1 px-2 border text-right">{{ number_format($range['end']) }}</td>
                    <td class="py-1 px-2 border">
                        <a href="{{ route('delivery_note_create.create', ['shipping_method_id' => $range['shipping_method_id'], 'start' => $range['start'], 'end' => $range['end']]) }}" class="btn bg-btn-enter text-white py-1 px-2" target="_blank">納品書</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-app-layout>