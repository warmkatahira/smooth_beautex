<?php

namespace App\Services\SystemAdmin\Base;

// モデル
use App\Models\Base;
use App\Models\ShippingMethod;
use App\Models\BaseShippingMethod;

class BaseCreateService
{
    // 倉庫を追加
    public function createBase($request)
    {
        // 倉庫を追加
        $base = Base::create([
            'base_id' => $request->base_id,
            'base_name' => $request->base_name,
            'mieru_customer_code' => $request->mieru_customer_code,
            'sort_order' => $request->sort_order,
        ]);
        // 配送方法を取得
        $shipping_methods = ShippingMethod::getAll()->get();
        // 配送方法の分だけループ処理
        foreach($shipping_methods as $shipping_method){
            // 倉庫別配送法を追加
            BaseShippingMethod::create([
                'base_id' => $base->base_id,
                'shipping_method_id' => $shipping_method->shipping_method_id,
            ]);
        }
    }
}