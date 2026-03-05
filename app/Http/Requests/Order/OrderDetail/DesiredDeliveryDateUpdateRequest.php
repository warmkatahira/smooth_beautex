<?php

namespace App\Http\Requests\Order\OrderDetail;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class DesiredDeliveryDateUpdateRequest extends BaseRequest
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
            'order_control_id'          => 'required|string|exists:orders,order_control_id',
            'desired_delivery_date'     => 'nullable|date',
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