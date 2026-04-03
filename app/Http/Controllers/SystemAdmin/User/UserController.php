<?php

namespace App\Http\Controllers\SystemAdmin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\User;
use App\Models\Role;
use App\Models\Company;
// サービス
use App\Services\SystemAdmin\User\UserSearchService;
// トレイト
use App\Traits\PaginatesResultsTrait;

class UserController extends Controller
{
    use PaginatesResultsTrait;

    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'ユーザー']);
        // インスタンス化
        $UserSearchService = new UserSearchService;
        // セッションを削除
        $UserSearchService->deleteSession();
        // セッションに検索条件を格納
        $UserSearchService->setSearchCondition($request);
        // 検索結果を取得
        $result = $UserSearchService->getSearchResult();
        // ページネーションを実施
        $users = $this->setPagination($result);
        // 権限を取得
        $roles = Role::getAll()->get();
        // 会社を取得
        $companies = Company::getAll()->get();
        return view('system_admin.user.index')->with([
            'users' => $users,
            'roles' => $roles,
            'companies' => $companies,
        ]);
    }
}