<x-app-layout>
    <table class="text-xs mt-3">
        <thead>
            <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                <th class="font-thin py-1 px-2 text-center">操作</th>
                <th class="font-thin py-1 px-2 text-center">受注区分</th>
                <th class="font-thin py-1 px-2 text-center">受注区分画像</th>
                <th class="font-thin py-1 px-2 text-center">荷送人名</th>
                <th class="font-thin py-1 px-2 text-center">並び順</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            @foreach($order_categories as $order_category)
                <tr class="text-left cursor-default whitespace-nowrap">
                    <td class="py-1 px-2 border">
                        <div class="flex flex-row gap-5">
                            <a href="{{ route('order_category_update.index', ['order_category_id' => $order_category->order_category_id]) }}" class="btn bg-btn-enter text-white py-1 px-2">更新</a>
                        </div>
                    </td>
                    <td class="py-1 px-2 border">{{ $order_category->order_category_name }}</td>
                    <td class="py-1 px-2 border text-center align-middle">
                        <img src="{{ asset('storage/order_category_images/'.$order_category->order_category_image_file_name) }}" class="w-12 inline-block">
                    </td>
                    <td class="py-1 px-2 border text-right">{{ $order_category->shipper->shipper_name }}</td>
                    <td class="py-1 px-2 border text-right">{{ $order_category->sort_order }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-app-layout>