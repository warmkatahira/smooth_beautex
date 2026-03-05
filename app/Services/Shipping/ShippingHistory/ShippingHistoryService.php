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
        if(!isset($request->search_type)){
            // 当日の日付をセッションに格納
            session(['search_shipping_date_from' => CarbonImmutable::now()->toDateString()]);
            session(['search_shipping_date_to' => CarbonImmutable::now()->toDateString()]);
        }
        // 「search」なら検索が実行されているので、検索条件をセット
        if($request->search_type === 'search'){
            session(['search_shipping_date_from' => $request->search_shipping_date_from]);
            session(['search_shipping_date_to' => $request->search_shipping_date_to]);
        }
        return;
    }
}