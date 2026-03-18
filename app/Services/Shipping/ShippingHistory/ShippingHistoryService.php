<?php

namespace App\Services\Shipping\ShippingHistory;
// その他
use Carbon\CarbonImmutable;

class ShippingHistoryService
{
    // セッションに検索条件を格納
    public function setSearchCondition($request)
    {
        // 変数が存在しない場合は検索が実行されていないので、初期条件をセット
        if(!isset($request->process_type)){
            // 当日の日付をセッションに格納
            session(['filter_shipping_date_from' => CarbonImmutable::now()->toDateString()]);
            session(['filter_shipping_date_to' => CarbonImmutable::now()->toDateString()]);
        }
        // 「filter」なら検索が実行されているので、検索条件をセット
        if($request->process_type === 'filter'){
            session(['filter_shipping_date_from' => $request->filter_shipping_date_from]);
            session(['filter_shipping_date_to' => $request->filter_shipping_date_to]);
        }
        return;
    }
}