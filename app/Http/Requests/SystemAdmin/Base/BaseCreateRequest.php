<?php

namespace App\Http\Requests\SystemAdmin\Base;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class BaseCreateRequest extends BaseRequest
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
            'base_id'               => 'required|string|max:10|unique:bases,base_id',
            'base_name'             => 'required|string|max:20|unique:bases,base_name',
            'mieru_customer_code'   => 'required|string|max:20',
            'sort_order'            => 'required|integer|min:1|max:100',
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