<?php

namespace App\Http\Requests\Setting\Shipper;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class ShipperUpdateRequest extends BaseRequest
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
            'shipper_id'                => 'required|exists:shippers,shipper_id',
            'shipper_company_name'      => 'required|string|max:50',
            'shipper_name'              => 'required|string|max:50',
            'shipper_zip_code'          => 'required|string|max:8',
            'shipper_address'           => 'required|string|max:255',
            'shipper_tel'               => 'required|string|max:13',
            'shipper_email'             => 'nullable|string|max:100',
            'shipper_invoice_no'        => 'nullable|string|max:30',
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