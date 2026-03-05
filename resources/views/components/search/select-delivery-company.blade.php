<div class="flex flex-col">
    <label for="search_shipping_method_id" class="mb-1">配送方法</label>
    <select id="search_shipping_method_id" name="search_shipping_method_id" class="search_element rounded border-gray-400 text-xs">
        <option value=""></option>
        @foreach($deliveryCompanies as $delivery_company)
            @foreach($delivery_company->shipping_methods as $shipping_method)
                <option value="{{ $shipping_method->shipping_method_id }}" @if(session('search_shipping_method_id') == $shipping_method->shipping_method_id) selected @endif>{{ $shipping_method->Delivery_Company_And_Shipping_Method }}</value>
            @endforeach
        @endforeach
    </select>
</div>