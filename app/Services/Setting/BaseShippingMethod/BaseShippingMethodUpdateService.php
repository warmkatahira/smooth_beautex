<?php

namespace App\Services\Setting\BaseShippingMethod;

// モデル
use App\Models\BaseShippingMethod;

class BaseShippingMethodUpdateService
{
    // 倉庫別配送方法を更新
    public function updateBaseShippingMethod($request)
    {
        // 倉庫別配送方法を更新
        BaseShippingMethod::getSpecify($request->base_shipping_method_id)->update([
            'setting_1' => $request->setting_1,
            'setting_2' => $request->setting_2,
            'setting_3' => $request->setting_3,
            'e_hiden_version_id' => $request->e_hiden_version_id,
        ]);
    }
}