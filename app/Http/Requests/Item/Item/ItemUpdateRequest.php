<?php

namespace App\Http\Requests\Item\Item;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class ItemUpdateRequest extends BaseRequest
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
            'item_jan_code'                 => 'required|string|max:13',
            'item_name'                     => 'required|string|max:255',
            'item_category'                 => 'nullable|string|max:20',
            'model_jan_code'                => 'nullable|string|max:13',
            'exp_start_position'            => 'nullable|integer|min:1',
            'lot_1_start_position'          => 'nullable|integer|min:1',
            'lot_1_length'                  => 'nullable|integer|min:1',
            'lot_2_start_position'          => 'required_with:lot_2_length|nullable|integer|min:1',
            'lot_2_length'                  => 'required_with:lot_2_start_position|nullable|integer|min:1',
            's_power_code'                  => 'required_with:model_jan_code|nullable|integer|min:1',
            's_power_code_start_position'   => 'required_with:model_jan_code|nullable|integer|min:1',
            'is_stock_managed'              => 'required|boolean',
            'image_file'                    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sort_order'                    => 'nullable|integer|min:1',
        ];
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'lot_2_start_position.required_with'        => 'LOT2桁数が入力されている場合、:attributeは必須です。',
            'lot_2_length.required_with'                => 'LOT2開始位置が入力されている場合、:attributeは必須です。',
            's_power_code.required_with'                => '代表JANコードが入力されている場合、:attributeは必須です。',
            's_power_code_start_position.required_with' => '代表JANコードが入力されている場合、:attributeは必須です。',
            'image_file.max'                            => ":attributeは:max KB以下の画像を選択してください。",
        ]);
    }

    public function attributes()
    {
        return parent::attributes();
    }
}