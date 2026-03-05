<?php

namespace App\Http\Controllers\SystemAdmin\OperationLog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\SystemAdmin\OperationLog\OperationLogSearchService;

class OperationLogController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '操作ログ']);
        // インスタンス化
        $OperationLogSearchService = new OperationLogSearchService;
        // セッションを削除
        $OperationLogSearchService->deleteSession();
        // セッションに検索条件を格納
        $OperationLogSearchService->setSearchCondition($request);
        // ログ情報を取得
        $log_contents = $OperationLogSearchService->getLogContent();
        // ページネーションを実施
        $operation_logs = $OperationLogSearchService->setPagination($log_contents);
        return view('system_admin.operation_log.index')->with([
            'operation_logs' => $operation_logs,
        ]);
    }
}
