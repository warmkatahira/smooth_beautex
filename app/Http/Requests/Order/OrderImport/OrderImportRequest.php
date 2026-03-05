<?php

namespace App\Http\Requests\Order\OrderImport;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class OrderImportRequest extends BaseRequest
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
            'select_file' => 'required|file|mimes:csv,txt|max:2048',
        ];
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'select_file.file'                  => "有効なファイルを選択してください。",
            'select_file.mimes'                 => "CSVファイルを選択してください。",
            'select_file.max'                   => "ファイルサイズは2MB以内にしてください。",
        ]);
    }

    public function attributes()
    {
        return parent::attributes();
    }
}