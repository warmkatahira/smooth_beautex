<?php

namespace App\Http\Requests\Order\OrderDetail;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class ShipAddressUpdateRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_control_id'      => 'required|string|exists:orders,order_control_id',
            'ship_country_code'     => 'nullable|string|max:5',
            'ship_province_code'    => 'nullable|string|max:10',
            'ship_province_name'    => 'nullable|string|max:5',
            'ship_city'             => 'nullable|string|max:255',
            'ship_address_1'        => 'required|string|max:255',
            'ship_address_2'        => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return parent::messages();
    }

    public function attributes()
    {
        return parent::attributes();
    }
}