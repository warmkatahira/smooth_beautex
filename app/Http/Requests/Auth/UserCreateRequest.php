<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class UserCreateRequest extends BaseRequest
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
            'user_id'       => "required|string|max:20|regex:/^[a-zA-Z0-9]+$/|unique:users,user_id",
            'last_name'     => "required|string|max:20",
            'first_name'    => "required|string|max:20",
            'email'         => "required|string|lowercase|email|max:255|unique:users,email",
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
