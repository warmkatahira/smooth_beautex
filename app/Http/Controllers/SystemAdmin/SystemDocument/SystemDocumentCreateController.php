<?php

namespace App\Http\Controllers\SystemAdmin\SystemDocument;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\SystemDocument;
// サービス
use App\Services\SystemAdmin\SystemDocument\SystemDocumentCreateService;
// リクエスト
use App\Http\Requests\SystemAdmin\SystemDocument\SystemDocumentCreateRequest;
// その他
use Illuminate\Support\Facades\DB;

class SystemDocumentCreateController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'システム資料追加']);
        return view('system_admin.system_document.create');
    }

    public function create(SystemDocumentCreateRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // インスタンス化
                $SystemDocumentCreateService = new SystemDocumentCreateService;
                // システム資料を追加
                $SystemDocumentCreateService->createSystemDocument($request);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->route('system_document.index')->with([
            'alert_type' => 'success',
            'alert_message' => 'システム資料を追加しました。',
        ]);
    }
}
