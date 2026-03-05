<?php

namespace App\Http\Requests\Setting\AutoProcess;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;
// 列挙
use App\Enums\AutoProcessEnum;

class AutoProcessCreateRequest extends BaseRequest
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
            'auto_process_name'     => 'required|string|max:30|unique:auto_processes,auto_process_name',
            'action_type'           => 'required|string|max:30',
            'condition_match_type'  => 'required|string|max:3',
            'is_active'             => 'required|boolean',
            'sort_order'            => 'required|integer|min:1',
        ];
        // 動的ルールを追加
        switch($this->input('action_type')){
            // 配送方法を変更
            case AutoProcessEnum::SHIPPING_METHOD_CHANGE:
                $rules['action_value'] = 'required|exists:shipping_methods,shipping_method_id';
                break;
            // 受注マークを更新
            case AutoProcessEnum::ORDER_MARK_UPDATE:
                $rules['action_value'] = 'required|max:10';
                break;
            // 出荷作業メモを更新
            case AutoProcessEnum::SHIPPING_WORK_MEMO_UPDATE:
                $rules['action_value'] = 'required|string|max:1000';
                break;
            // 受注商品を追加
            case AutoProcessEnum::ORDER_ITEM_CREATE:
                $rules['order_item_code'] = 'required|string|max:255';
                $rules['order_item_name'] = 'required|string|max:255';
                $rules['order_quantity'] = 'required|integer|min:1';
                break;
        }
        return $rules;
    }

    public function messages()
    {
        return parent::messages();
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'sort_order'    => "実行順",
        ]);
    }
}