<?php

namespace App\Http\Requests\Stock\Stock;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;
// モデル
use App\Models\Stock;
use App\Models\OrderItemLot;
// 列挙
use App\Enums\OrderStatusEnum;

class StockUpdateRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // 在庫を取得
            $stock = Stock::getSpecify($this->input('stock_id'))->first();
            // 取得できなかった場合
            if(!$stock){
                // 処理を抜ける
                return;
            }
            // 出荷作業中のLOT・EXPは変更不可
            $locked = OrderItemLot::where('lot', $stock->lot)
                                    ->where('exp', $stock->exp)
                                    ->whereHas('order_item.order', function ($query) {
                                        $query->where('order_status_id', OrderStatusEnum::SAGYO_CHU);
                                    })
                                    ->exists();
            // 出荷作業中の場合
            if($locked){
                // エラーを返す
                $validator->errors()->add('lot', '出荷検品でスキャンされたLOT・EXPは変更できません。');
                return;
            }
            // 同じ組み合わせの在庫が存在するか確認
            $exists = Stock::where('base_id', $stock->base_id)
                            ->where('item_id', $stock->item_id)
                            ->where('lot', $this->input('lot'))
                            ->where('exp', $this->input('exp'))
                            ->where('stock_id', '!=', $this->input('stock_id'))
                            ->exists();
            // 存在している場合
            if($exists){
                // エラーを返す
                $validator->errors()->add('lot', '同じLOT・EXPの組み合わせが既に存在します。');
            }
        });
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'stock_id'  => 'required|exists:stocks,stock_id',
            'lot'       => 'nullable|string|max:20',
            'exp'       => 'nullable|string|size:6|date_format:Ym',
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