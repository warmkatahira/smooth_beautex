@props([
    'deliveryCompanies',
    'index' => null,
    'value' => null,
])

<select name="value[]" class="text-xs w-full">
    <option value=""></option>
    @foreach($deliveryCompanies as $delivery_company)
        @foreach($delivery_company->shipping_methods as $shipping_method)
            <option value="{{ $shipping_method->shipping_method_id }}" @if(old("value.$index", $value ?? '') == $shipping_method->shipping_method_id) selected @endif>
                {{ $shipping_method->Delivery_Company_And_Shipping_Method }}
            </option>
        @endforeach
    @endforeach
</select>