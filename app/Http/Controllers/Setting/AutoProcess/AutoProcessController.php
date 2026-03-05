<?php

namespace App\Http\Controllers\Setting\AutoProcess;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\AutoProcess;
// 列挙
use App\Enums\AutoProcessEnum;
// サービス
use App\Services\Setting\AutoProcess\AutoProcessSearchService;

class AutoProcessController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '自動処理']);
        // インスタンス化
        $AutoProcessSearchService = new AutoProcessSearchService;
        // セッションを削除
        $AutoProcessSearchService->deleteSession();
        // セッションに検索条件を格納
        $AutoProcessSearchService->setSearchCondition($request);
        // 検索結果を取得
        $result = $AutoProcessSearchService->getSearchResult();
        // ページネーションを実施
        $auto_processes = $AutoProcessSearchService->setPagination($result);
        // アクション区分を取得
        $action_types = AutoProcessEnum::ACTION_TYPE_LIST;
        return view('setting.auto_process.index')->with([
            'auto_processes' => $auto_processes,
            'action_types' => $action_types,
        ]);
    }
}