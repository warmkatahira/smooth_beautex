<?php

namespace App\Http\Requests\Setting\ShippingBase;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class ShippingBaseUpdateRequest extends BaseRequest
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
            'prefecture_id'             => 'required|exists:prefectures,prefecture_id',
            'shipping_base_id'          => 'required|exists:bases,base_id',
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