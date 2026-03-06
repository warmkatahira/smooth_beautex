@props([
    'label',
    'id',
    'name',
    'deliveryCompanies',
    'value',
])

<div class="flex flex-col bg-white py-2 px-3 w-form-div">
    <label for="{{ $id }}" class="text-gray-800 py-2.5 pl-3 relative">
        {{ $label }}
        <span class="bg-pink-200 text-red-600 text-xs px-2 py-1 rounded">必須</span>
    </label>
    <select id="{{ $id }}" name="{{ $name }}" class="w-full text-sm border border-gray-400">
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