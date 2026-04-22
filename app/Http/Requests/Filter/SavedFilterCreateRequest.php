<?php

namespace App\Http\Requests\Filter;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class SavedFilterCreateRequest extends BaseRequest
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
            'filter_page'           => 'required|string|max:30',
            'filter_name'           => 'required|string|max:20',
            'filter_conditions'     => 'required|string',
        ];
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'filter_page.max'      => ':attributeが正しくありません。',
        ]);
    }

    public function attributes()
    {
        return parent::attributes();
    }
}