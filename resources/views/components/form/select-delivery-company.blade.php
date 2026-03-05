<div class="flex flex-row">
    <label for="{{ $id }}" class="w-56 bg-black text-white py-2.5 pl-3 relative">
        {{ $label }}
        <span class="absolute right-2 top-1/2 -translate-y-1/2 bg-white text-red-600 text-xs px-1.5 py-0.5 rounded">必須</span>
    </label>
    <select id="{{ $id }}" name="{{ $name }}" class="w-96 text-sm">
        <option value=""></option>
        @foreach($deliveryCompanies as $delivery_company)
            @foreach($delivery_company->shipping_methods as $shipping_method)
                <option value="{{ $shipping_method->shipping_method_id }}"
                    {{ old($name, $value ?? '') == $shipping_method->shipping_method_id ? 'selected' : '' }}>
                    {{ $shipping_method->Delivery_Company_And_Shipping_Method }}
                </option>
            @endforeach
        @endforeach
    </select>
</div>