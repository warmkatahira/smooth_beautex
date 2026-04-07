<?php

namespace App\Http\Requests\Order\OrderMgt;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;
// その他
use Carbon\CarbonImmutable;

class OrderSearchRequest extends BaseRequest
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
        $dateRangeRule = function (string $fromKey) {
            return [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($fromKey) {
                    $from = request($fromKey);
                    if($from && $value){
                        $fromDate = CarbonImmutable::parse($from);
                        $toDate   = CarbonImmutable::parse($value);
                        if($toDate->lt($fromDate)){
                            $fail('終了日は開始日以降の日付を指定してください。');
                        }
                        if($fromDate->diffInDays($toDate) > 100){
                            $fail('検索範囲は100日以内で指定してください。');
                        }
                    }
                },
            ];
        };
        return [
            'filter_order_import_date_from'      => ['nullable', 'date'],
            'filter_order_import_date_to'        => $dateRangeRule('filter_order_import_date_from'),
            'filter_order_date_from'             => ['nullable', 'date'],
            'filter_order_date_to'               => $dateRangeRule('filter_order_date_from'),
            'filter_shipping_date_from'          => ['nullable', 'date'],
            'filter_shipping_date_to'            => $dateRangeRule('filter_shipping_date_from'),
            'filter_desired_delivery_date_from'  => ['nullable', 'date'],
            'filter_desired_delivery_date_to'    => $dateRangeRule('filter_desired_delivery_date_from'),
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