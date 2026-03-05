<?php

namespace App\Http\Controllers\Setting\ShippingBase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Setting\ShippingBase\ShippingBaseUpdateService;
// リクエスト
use App\Http\Requests\Setting\ShippingBase\ShippingBaseUpdateRequest;
// その他
use Illuminate\Support\Facades\DB;

class ShippingBaseUpdateController extends Controller
{
    public function update(ShippingBaseUpdateRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $ShippingBaseUpdateService = new ShippingBaseUpdateService;
                // 出荷倉庫を更新
                $ShippingBaseUpdateService->updateShippingBase($request);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => '出荷倉庫の更新に失敗しました。',
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '出荷倉庫を更新しました。',
        ]);
    }
}