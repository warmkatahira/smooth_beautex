<?php

namespace App\Services\SystemAdmin\Base;

// モデル
use App\Models\Base;

class BaseUpdateService
{
    // 倉庫を更新
    public function updateBase($request)
    {
        // 倉庫を取得
        $base = Base::getSpecify($request->base_id)->first();
        // 倉庫を更新
        $base->update([
            'base_name' => $request->base_name,
            'base_color_code' => $request->base_color_code,
            'mieru_customer_code' => $request->mieru_customer_code,
            'sort_order' => $request->sort_order,
        ]);
        return;
    }
}