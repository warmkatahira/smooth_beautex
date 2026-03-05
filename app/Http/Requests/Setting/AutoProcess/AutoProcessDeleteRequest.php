<?php

namespace App\Http\Requests\Setting\AutoProcess;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class AutoProcessDeleteRequest extends BaseRequest
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
            'auto_process_id'     => 'required|exists:auto_processes,auto_process_id',
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