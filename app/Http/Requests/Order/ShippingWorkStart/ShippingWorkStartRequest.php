<?php

namespace App\Http\Requests\Order\ShippingWorkStart;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class ShippingWorkStartRequest extends BaseRequest
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
            'shipping_group_name'       => 'required|string|max:20|regex:/^[ぁ-んァ-ヶー一-龯a-zA-Z0-9_-]+$/u',
            'estimated_shipping_date'   => 'required|date',
        ];
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'regex'                             => ":attributeに使用できない文字が含まれています。",
        ]);
    }

    public function attributes()
    {
        return parent::attributes();
    }
}