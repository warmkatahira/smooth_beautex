<?php

namespace App\Http\Controllers\SystemAdmin\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Base;
// サービス
use App\Services\SystemAdmin\Base\BaseUpdateService;
// リクエスト
use App\Http\Requests\SystemAdmin\Base\BaseUpdateRequest;
// その他
use Illuminate\Support\Facades\DB;

class BaseUpdateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '倉庫更新']);
        // 倉庫を取得
        $base = Base::getSpecify($request->base_id)->first();
        return view('system_admin.base.update')->with([
            'base' => $base,
        ]);
    }

    public function update(BaseUpdateRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $BaseUpdateService = new BaseUpdateService;
                // 倉庫を更新
                $BaseUpdateService->updateBase($request);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->route('base.index')->with([
            'alert_type' => 'success',
            'alert_message' => '倉庫を更新しました。',
        ]);
    }
}