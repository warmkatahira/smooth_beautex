<?php

namespace App\Http\Requests\Item\ItemQrAnalysis;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class ItemQrAnalysisResultUpdateRequest extends BaseRequest
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
            'item_qr_analysis_history_id' => 'required|exists:item_qr_analysis_histories,item_qr_analysis_history_id',
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