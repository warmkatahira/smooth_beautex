<?php

namespace App\Http\Controllers\SystemAdmin\SystemDocument;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\SystemAdmin\SystemDocument\SystemDocumentDeleteService;
// その他
use Illuminate\Support\Facades\DB;

class SystemDocumentDeleteController extends Controller
{
    public function delete(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // インスタンス化
                $SystemDocumentDeleteService = new SystemDocumentDeleteService;
                // システム資料を削除
                $SystemDocumentDeleteService->deleteSystemDocument($request->system_document_id);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => 'システム資料の削除に失敗しました。',
            ]);
        }
        return redirect()->route('system_document.index')->with([
            'alert_type' => 'success',
            'alert_message' => 'システム資料を削除しました。',
        ]);
    }
}
