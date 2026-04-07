<?php

namespace App\Services\Setting\OrderCategory;

// モデル
use App\Models\OrderCategory;

class OrderCategoryUpdateService
{
    // 受注区分を更新
    public function updateOrderCategory($request)
    {
        // 受注区分を取得
        $order_category = OrderCategory::getSpecify($request->order_category_id)->first();
        // 受注区分を更新
        $order_category->update([
            'order_category_name' => $request->order_category_name,
            'mall_id' => $request->mall_id,
            'shipper_id' => $request->shipper_id,
            'sort_order' => $request->sort_order,
        ]);
    }
}