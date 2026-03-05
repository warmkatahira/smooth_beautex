<div class="disable_scrollbar flex flex-grow overflow-scroll mt-3">
        <div class="shipping_base_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
            <table class="text-xs">
                <thead>
                    <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                        <th class="font-thin py-1 px-2 text-center">都道府県</th>
                        <th class="font-thin py-1 px-2 text-center">出荷倉庫</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($prefectures as $prefecture)
                        <tr style="--base-color: {{ $prefecture->base->base_color_code }};" class="bg-[var(--base-color)] text-left cursor-default whitespace-nowrap">
                            <td class="py-1 px-2 border text-center">{{ $prefecture->prefecture_name }}</td>
                            <td class="py-1 px-2 border text-center">
                                <select id="{{ $prefecture->prefecture_id }}" name="shipping_base_id" class="shipping_base_change text-xs py-1" data-prefecture-name="{{ $prefecture->prefecture_name }}">
                                    @foreach($bases as $base)
                                        <option value="{{ $base->base_id }}" @if($prefecture->shipping_base_id === $base->base_id) selected @endif>{{ $base->base_name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <form method="POST" action="{{ route('shipping_base_update.update') }}" id="shipping_base_update_form">
        @csrf
        <input type="hidden" id="prefecture_id" name="prefecture_id">
        <input type="hidden" id="shipping_base_id" name="shipping_base_id">
    </form>