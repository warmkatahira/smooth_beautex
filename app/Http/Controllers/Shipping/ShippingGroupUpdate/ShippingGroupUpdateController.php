<?php

namespace App\Http\Controllers\Shipping\ShippingGroupUpdate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Shipping\ShippingGroupUpdate\ShippingGroupUpdateService;
// リクエスト
use App\Http\Requests\Shipping\ShippingGroupUpdate\ShippingGroupUpdateRequest;
// その他
use Illuminate\Support\Facades\DB;

class ShippingGroupUpdateController extends Controller
{
    public function update(ShippingGroupUpdateRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $ShippingGroupUpdateService = new ShippingGroupUpdateService;
                // 出荷グループをロックして取得
                $shipping_group = $ShippingGroupUpdateService->getShippingGroup($request);
                // 出荷グループを更新
                $ShippingGroupUpdateService->updateShippingGroup($request, $shipping_group);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect(session('back_url_1'))->with([
            'alert_type' => 'success',
            'alert_message' => '出荷グループを更新しました。',
        ]);
    }
}