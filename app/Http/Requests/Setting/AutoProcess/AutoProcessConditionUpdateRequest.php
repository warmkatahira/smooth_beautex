<?php

namespace App\Http\Requests\Setting\AutoProcess;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;
// 列挙
use App\Enums\AutoProcessEnum;
// その他
use Illuminate\Validation\Rule;

class AutoProcessConditionUpdateRequest extends BaseRequest
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
        // 共通ルール
        $rules = [
            'auto_process_id'       => 'required|exists:auto_processes,auto_process_id',
            'column_name.*'         => ['required', Rule::in(array_keys(AutoProcessEnum::COLUMN_NAME_LIST))],
            'operator.*'            => ['required', Rule::in(array_keys(AutoProcessEnum::OPERATOR_LIST))],
        ];
        // 動的ルールを追加
        foreach($this->input('column_name', []) as $index => $column){
            // operatorの値を取得
            $operator = $this->input("operator.$index");
            // valueが必須かどうかを取得
            $is_value_required = !in_array($operator, [
                AutoProcessEnum::IS_NULL,
                AutoProcessEnum::IS_NOT_NULL,
            ]);
            switch($column){
                // 配送方法
                case AutoProcessEnum::SHIPPING_METHOD_ID:
                    $rules["value.$index"] = ($is_value_required ? 'required|' : 'nullable|') . 'exists:shipping_methods,shipping_method_id';
                    break;
                // 出荷作業メモ
                case AutoProcessEnum::SHIPPING_WORK_MEMO:
                    $rules["value.$index"] = ($is_value_required ? 'required|' : 'nullable|') . 'string|max:20';
                    break;
                // 配送先都道府県
                case AutoProcessEnum::SHIP_PREFECTURE_NAME:
                    $rules["value.$index"] = ($is_value_required ? 'required|' : 'nullable|') . 'string|max:4';
                    break;
                // 配送先名
                case AutoProcessEnum::SHIP_NAME:
                    $rules["value.$index"] = ($is_value_required ? 'required|' : 'nullable|') . 'string|max:20';
                    break;
                // 受注マーク
                case AutoProcessEnum::ORDER_MARK:
                    $rules["value.$index"] = ($is_value_required ? 'required|' : 'nullable|') . 'string|max:10';
                    break;
                // 受注商品コード
                case AutoProcessEnum::ORDER_ITEM_CODE:
                    $rules["value.$index"] = ($is_value_required ? 'required|' : 'nullable|') . 'string|max:255';
                    break;
                // 受注商品名
                case AutoProcessEnum::ORDER_ITEM_NAME:
                    $rules["value.$index"] = ($is_value_required ? 'required|' : 'nullable|') . 'string|max:255';
                    break;
                // 出荷数
                case AutoProcessEnum::ORDER_QUANTITY:
                    $rules["value.$index"] = ($is_value_required ? 'required|' : 'nullable|') . 'integer';
                    break;
            }
        }
        return $rules;
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'column_name.*.in'      => '不正な:attributeが選択されています。',
            'operator.*.in'         => '不正な:attributeが選択されています。',
        ]);
    }

    public function attributes()
    {
        return parent::attributes();
    }
}