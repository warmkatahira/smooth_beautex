@props([
    'id',
    'name',
    'deliveryCompanies',
])

<th class="px-3">
    <div class="flex flex-row items-center">
        <select id="{{ $id }}" name="{{ $name }}" class="search_element rounded border-gray-400 text-xs font-thin mx-2 py-1">
            <option value=""></option>
            @foreach($deliveryCompanies as $delivery_company)
                <optgroup label="{{ $delivery_company->delivery_company }}">
                    @foreach($delivery_company->shipping_methods as $shipping_method)
                        <option value="{{ $shipping_method->shipping_method_id }}" @if(session()->has($name) && (string)session($name) === (string)$shipping_method->shipping_method_id) selected @endif>{{ $shipping_method->shipping_method }}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
        <button type="button" class="filter_clear btn hidden" data-target="{{ $name }}"><i class="las la-times la-lg text-red-500 pr-2"></i></button>
    </div>
</th>