<div class="flex flex-col w-full">
    <p class="mt-2">{{ $title }}</p>
    <div class="shipping_inspection_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table id="{{ $tableId }}" class="text-sm w-full">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 w-2/12">商品JANコード</th>
                    <th class="font-thin py-1 px-2 w-2/12">代表JANコード</th>
                    <th class="font-thin py-1 px-2 w-8/12">商品名</th>
                    <th class="font-thin py-1 px-2 text-right w-1/12">出荷数</th>
                    <th class="font-thin py-1 px-2 text-right w-1/12">検品数</th>
                </tr>
            </thead>
            <tbody class="bg-white">
            </tbody>
        </table>
    </div>
</div>