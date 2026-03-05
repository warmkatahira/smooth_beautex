<?php

namespace App\Services\Shipping\ShippingGroupUpdate;

// モデル
use App\Models\ShippingGroup;

class ShippingGroupUpdateService
{
    // 出荷グループをロックして取得
    public function getShippingGroup($request)
    {
        // 出荷グループを取得
        return ShippingGroup::getSpecify($request->shipping_group_id)->lockForUpdate()->first();
    }

    // 出荷グループを更新
    public function updateShippingGroup($request, $shipping_group)
    {
        // 出荷グループを更新
        $shipping_group->update([
            'shipping_group_name'      => $request->shipping_group_name,
            'estimated_shipping_date'  => $request->estimated_shipping_date,
        ]);
        return;
    }
}