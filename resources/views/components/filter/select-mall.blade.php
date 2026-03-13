@props([
    'id',
    'name',
    'malls',
])

<th class="px-3">
    <div class="flex flex-row items-center">
        <select id="{{ $id }}" name="{{ $name }}" class="search_element rounded border-gray-400 text-xs font-thin mx-2 py-1">
            <option value=""></option>
            @foreach($malls as $mall)
                <optgroup label="{{ $mall->mall_name }}">
                    @foreach($mall->order_categories as $order_category)
                        <option value="{{ $order_category->order_category_id }}" @if(session()->has($name) && (string)session($name) === (string)$order_category->order_category_id) selected @endif>{{ $order_category->order_category_name }}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
        <button type="button" class="filter_clear btn hidden" data-target="{{ $name }}"><i class="las la-times la-lg text-red-500 pr-2"></i></button>
    </div>
</th>