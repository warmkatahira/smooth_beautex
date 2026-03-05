<x-app-layout>
    <table class="text-xs mt-3">
        <thead>
            <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                <th class="font-thin py-1 px-2 text-center">操作</th>
                <th class="font-thin py-1 px-2 text-center">倉庫名</th>
                <th class="font-thin py-1 px-2 text-center">運送会社</th>
                <th class="font-thin py-1 px-2 text-center">配送方法</th>
                <th class="font-thin py-1 px-2 text-center">設定1</th>
                <th class="font-thin py-1 px-2 text-center">設定2</th>
                <th class="font-thin py-1 px-2 text-center">設定3</th>
                <th class="font-thin py-1 px-2 text-center">e飛伝バージョン</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            @foreach($bases as $base)
                @foreach($base->base_shipping_methods as $base_shipping_method)
                    <tr class="text-left cursor-default whitespace-nowrap">
                        <td class="py-1 px-2 border">
                            <div class="flex flex-row gap-5">
                                <a href="{{ route('base_shipping_method_update.index', ['base_shipping_method_id' => $base_shipping_method->base_shipping_method_id]) }}" class="btn bg-btn-enter text-white py-1 px-2">更新</a>
                            </div>
                        </td>
                        <td class="py-1 px-2 border">{{ $base->base_name }}</td>
                        <td class="py-1 px-2 border">
                            <img src="{{ asset('image/'.$base_shipping_method->shipping_method->delivery_company->company_image) }}" class="inline-block">
                        </td>
                        <td class="py-1 px-2 border">{{ $base_shipping_method->shipping_method->shipping_method }}</td>
                        <td class="py-1 px-2 border text-center">{{ $base_shipping_method->setting_1 }}</td>
                        <td class="py-1 px-2 border text-center">{{ $base_shipping_method->setting_2 }}</td>
                        <td class="py-1 px-2 border text-center">{{ $base_shipping_method->setting_3 }}</td>
                        <td class="py-1 px-2 border text-center">{{ $base_shipping_method->e_hiden_version?->e_hiden_version }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</x-app-layout>