<?php

namespace App\Services\Setting\ShippingBase;

// モデル
use App\Models\Prefecture;

class ShippingBaseUpdateService
{
    // 出荷倉庫を更新
    public function updateShippingBase($request)
    {
        // 出荷倉庫を更新
        Prefecture::where('prefecture_id', $request->prefecture_id)->update([
            'shipping_base_id' => $request->shipping_base_id,
        ]);
    }
}