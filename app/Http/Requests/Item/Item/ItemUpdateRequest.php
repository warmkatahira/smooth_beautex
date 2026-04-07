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

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_stock_managed'  => $this->boolean('is_stock_managed'),
            'is_lot_managed'    => $this->boolean('is_lot_managed'),
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
            'item_jan_code'                 => 'required|string|max:13',
            'color_id'                      => 'nullable|string|max:20',
            'color_row'                     => 'nullable|integer|between:0,255',
            'item_name'                     => 'required|string|max:255',
            'item_category_1'               => 'nullable|string|max:20',
            'item_category_2'               => 'nullable|string|max:20',
            'brand'                         => 'nullable|string|max:50',
            'wearing_period'                => 'nullable|string|max:20',
            'quantity_per_box'              => 'nullable|string|max:20',
            'manufacturer'                  => 'nullable|string|max:20',
            'supplier'                      => 'nullable|string|max:20',
            'is_lot_managed'                => 'required|boolean',
            'model_jan_code'                => 'nullable|string|max:13',
            'exp_start_position'            => 'nullable|integer|between:1,255',
            'lot_1_start_position'          => 'required_if:is_lot_managed,1|required_with:lot_1_length|nullable|integer|between:1,255',
            'lot_1_length'                  => 'required_if:is_lot_managed,1|required_with:lot_1_start_position|nullable|integer|between:1,255',
            'lot_2_start_position'          => 'required_with:lot_2_length|nullable|integer|between:1,255',
            'lot_2_length'                  => 'required_with:lot_2_start_position|nullable|integer|between:1,255',
            's_power_code'                  => 'required_with:model_jan_code|nullable|integer|between:200,240',
            's_power_code_start_position'   => 'required_with:model_jan_code|nullable|integer|between:1,255',
            'is_stock_managed'              => 'required|boolean',
            'image_file'                    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'country_of_origin'             => 'nullable|string|max:10',
            'hs_code'                       => 'nullable|string|max:10',
            'item_weight_g'                 => 'nullable|integer|min:1',
            'sort_order'                    => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'lot_1_start_position.required_if'          => 'ロット管理が有効な場合、:attributeは必須です。',
            'lot_1_length.required_if'                  => 'ロット管理が有効な場合、:attributeは必須です。',
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