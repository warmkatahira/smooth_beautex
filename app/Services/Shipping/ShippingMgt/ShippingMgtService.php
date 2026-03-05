<?php

namespace App\Services\Shipping\ShippingMgt;

// モデル
use App\Models\ShippingMethod;
use App\Models\ShippingGroup;

class ShippingMgtService
{
    // 出荷グループを取得
    public function getShippingGroup($search_shipping_group_id)
    {
        // パラメータが存在しない場合
        if(!isset($search_shipping_group_id)){
            // セッションにnullを格納
            session(['search_shipping_group_id' => null]);
            // nullを返す
            return null;
        }
        // セッションにパラメータを格納
        session(['search_shipping_group_id' => $search_shipping_group_id]);
        // 出荷グループを取得
        return ShippingGroup::getSpecify($search_shipping_group_id)->first();;
    }

    // 出荷グループに存在する配送方法を取得
    public function getShippingMethod()
    {
        // 出荷グループの選択がされていない場合
        if(is_null(session('search_shipping_group_id'))){
            return array();
        }
        // 出荷グループに存在する配送方法を取得
        return ShippingMethod::join('orders', 'orders.shipping_method_id', 'shipping_methods.shipping_method_id')
                                ->join('delivery_companies', 'delivery_companies.delivery_company_id', 'shipping_methods.delivery_company_id')
                                ->where('shipping_group_id', session('search_shipping_group_id'))
                                ->select('delivery_company', 'shipping_method', 'shipping_methods.shipping_method_id')
                                ->groupBy('delivery_company', 'shipping_methods.shipping_method_id')
                                ->orderBy('delivery_companies.delivery_company_id', 'asc')
                                ->orderBy('shipping_methods.shipping_method_id', 'asc')
                                ->get();
    }
}