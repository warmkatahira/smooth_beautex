<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class UserPasswordChangeRequest extends BaseRequest
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
            'password'      => "required|confirmed|string|min:8|max:20|regex:/^[a-zA-Z0-9]+$/",
        ];
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'regex'     => ':attributeは英数字のみで入力して下さい。',
        ]);
    }

    public function attributes()
    {
        return parent::attributes();
    }
}
