<?php

namespace App\Http\Requests\Order\OrderItemLot;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;
// モデル
use App\Models\OrderItemLot;
use App\Models\OrderItem;

class OrderItemLotUpdateRequest extends BaseRequest
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
            'lots'                          => ['required', 'array'],
            'lots.*.order_item_lot_id'      => ['required', 'integer', 'exists:order_item_lots,order_item_lot_id'],
            'lots.*.lot'                    => ['nullable', 'string', 'max:20'],
            'lots.*.exp'                    => ['nullable', 'regex:/^\d{4}(0[1-9]|1[0-2])$/'],
            'lots.*.quantity'               => ['required', 'integer', 'min:1'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
        $lots = collect($this->lots);
        $grouped = $lots->groupBy(function ($lot) {
            $orderItemLot = OrderItemLot::find($lot['order_item_lot_id']);
            return $orderItemLot?->order_item_id;
        });
        foreach($grouped as $orderItemId => $lotsInItem){
            $orderItem = OrderItem::find($orderItemId);
            if(!$orderItem) continue;
            // ロット管理ありの場合、lot が null は不可
            if ($orderItem->item?->is_lot_managed) {
                foreach($lotsInItem as $index => $lotData){
                    if(is_null($lotData['lot'] ?? null)){
                        $validator->errors()->add(
                            "lots.{$index}.lot",
                            "商品「{$orderItem->order_item_name}」のLOTは必須です。"
                        );
                    }
                }
            }
            // 同一 order_item_id 内で lot の重複チェック
            $lotValues = $lotsInItem->pluck('lot')->filter(); // nullは除外
            if($lotValues->count() !== $lotValues->unique()->count()){
                $validator->errors()->add(
                    'lots',
                    "商品「{$orderItem->order_item_name}」に同じLOTが複数存在します。"
                );
            }
            // shipping_quantity と quantity の合計チェック
            $totalQuantity = $lotsInItem->sum('quantity');
            if($totalQuantity !== (int) $orderItem->shipping_quantity){
                $validator->errors()->add(
                    'lots',
                    "商品「{$orderItem->order_item_name}」のLOT数量合計（{$totalQuantity}）が出荷数（{$orderItem->shipping_quantity}）と一致しません。"
                );
            }
        }
    });
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'lots' => collect($this->lots)->map(fn($lot) => array_merge($lot, [
                'exp' => ($lot['exp'] ?? '') === '' ? null : $lot['exp'],
                'lot' => ($lot['lot'] ?? '') === '' ? null : $lot['lot'],
            ]))->toArray(),
        ]);
    }

    public function messages()
    {
        return parent::messages();
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'lots'                          => 'LOT一覧',
            'lots.*.order_item_lot_id'      => 'LOT ID',
            'lots.*.lot'                    => 'LOT',
            'lots.*.exp'                    => 'EXP',
            'lots.*.quantity'               => '数量',
        ]);
    }
}