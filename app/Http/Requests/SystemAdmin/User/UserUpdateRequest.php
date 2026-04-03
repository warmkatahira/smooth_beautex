<?php

namespace App\Http\Requests\SystemAdmin\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class UserUpdateRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status'                    => $this->boolean('status'),
            'is_must_change_password'   => $this->boolean('is_must_change_password'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'last_name'                 => 'required|string|max:20',
            'first_name'                => 'nullable|string|max:20',
            'email'                     => 'nullable|email|unique:users,email,'.$this->user_no.',user_no',
            'status'                    => 'required|boolean',
            'role_id'                   => 'required|exists:roles,role_id',
            'company_id'                => 'required|exists:companies,company_id',
            'is_must_change_password'   => 'required|boolean',
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