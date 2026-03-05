<?php

namespace App\Http\Controllers\SystemAdmin\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\SystemAdmin\Base\BaseCreateService;
// リクエスト
use App\Http\Requests\SystemAdmin\Base\BaseCreateRequest;
// その他
use Illuminate\Support\Facades\DB;

class BaseCreateController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '倉庫追加']);
        return view('system_admin.base.create')->with([
        ]);
    }

    public function create(BaseCreateRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $BaseCreateService = new BaseCreateService;
                // 倉庫を追加
                $BaseCreateService->createBase($request);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->route('base.index')->with([
            'alert_type' => 'success',
            'alert_message' => '倉庫を追加しました。',
        ]);
    }
}