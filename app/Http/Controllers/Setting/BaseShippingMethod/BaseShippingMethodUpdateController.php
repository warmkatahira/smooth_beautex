<?php

namespace App\Http\Controllers\Setting\BaseShippingMethod;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\BaseShippingMethod;
use App\Models\EHidenVersion;
// サービス
use App\Services\Setting\BaseShippingMethod\BaseShippingMethodUpdateService;
// リクエスト
use App\Http\Requests\Setting\BaseShippingMethod\BaseShippingMethodUpdateRequest;
// その他
use Illuminate\Support\Facades\DB;

class BaseShippingMethodUpdateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '倉庫別配送方法更新']);
        // 配送方法を取得
        $base_shipping_method = BaseShippingMethod::getSpecify($request->base_shipping_method_id)->with([
                        'shipping_method.delivery_company',
                        'base',
                    ])
                    ->first();
        // e飛伝バージョンを取得
        $e_hiden_versions = EHidenVersion::getAll()->get();
        return view('setting.base_shipping_method.update')->with([
            'base_shipping_method' => $base_shipping_method,
            'e_hiden_versions' => $e_hiden_versions,
        ]);
    }

    public function update(BaseShippingMethodUpdateRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $BaseShippingMethodUpdateService = new BaseShippingMethodUpdateService;
                // 倉庫別配送方法を更新
                $BaseShippingMethodUpdateService->updateBaseShippingMethod($request);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => '倉庫別配送方法の更新に失敗しました。',
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '倉庫別配送方法を更新しました。',
        ]);
    }
}