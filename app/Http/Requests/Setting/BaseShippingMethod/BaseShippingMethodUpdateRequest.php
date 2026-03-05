<?php

namespace App\Http\Requests\Setting\BaseShippingMethod;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class BaseShippingMethodUpdateRequest extends BaseRequest
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
            'base_shipping_method_id'   => 'required|exists:base_shipping_methods,base_shipping_method_id',
            'setting_1'                 => 'nullable|string|max:20',
            'setting_2'                 => 'nullable|string|max:20',
            'setting_3'                 => 'nullable|string|max:20',
            'e_hiden_version_id'        => 'nullable|exists:e_hiden_versions,e_hiden_version_id',
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