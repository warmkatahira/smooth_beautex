<?php

namespace App\Http\Controllers\Shipping\ReturnToShukkaMachi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Shipping\ReturnToShukkaMachi\ReturnToShukkaMachiService;
// 列挙
use App\Enums\OrderStatusEnum;
// その他
use Illuminate\Support\Facades\DB;

class ReturnToShukkaMachiController extends Controller
{
    public function enter(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // インスタンス化
                $ReturnToShukkaMachiService = new ReturnToShukkaMachiService;
                // 出荷待ちに戻せる受注であるか確認
                $ReturnToShukkaMachiService->checkUpdatableReturnToShukkaMachi($request->chk);
                // 出荷待ちに戻す処理
                $ReturnToShukkaMachiService->procReturnToShukkaMachi($request->chk);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect(session('back_url_1'))->with([
            'alert_type' => 'success',
            'alert_message' => count($request->chk).'件の受注を出荷待ちへ戻しました。',
        ]);
    }
}
