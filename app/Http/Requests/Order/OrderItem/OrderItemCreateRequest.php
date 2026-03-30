<?php

namespace App\Http\Requests\Order\OrderItem;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class OrderItemCreateRequest extends BaseRequest
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
            'order_item_code'       => 'required|string|exists:items,item_code',
            'shipping_quantity'     => 'required|integer|min:1',
            'order_item_unit_price' => 'required|integer|min:0',
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