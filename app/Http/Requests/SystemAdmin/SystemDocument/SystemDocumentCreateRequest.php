<?php

namespace App\Http\Requests\SystemAdmin\SystemDocument;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class SystemDocumentCreateRequest extends BaseRequest
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
            'select_file'   => 'required|file|mimes:pdf',
            'sort_order'    => 'required|integer|min:1|max:200',
            'is_internal'   => 'required|boolean',
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
