<?php

namespace App\Http\Controllers\SystemAdmin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\User;
use App\Models\Role;
use App\Models\Company;
// サービス
use App\Services\SystemAdmin\User\UserCreateService;
// リクエスト
use App\Http\Requests\SystemAdmin\User\UserCreateRequest;
// その他
use Illuminate\Support\Facades\DB;

class UserCreateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'ユーザー追加']);
        // 権限を取得
        $roles = Role::getAll()->get();
        // 会社を取得
        $companies = Company::getAll()->get(); 
        return view('system_admin.user.create')->with([
            'roles' => $roles,
            'companies' => $companies,
        ]);
    }

    public function create(UserCreateRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $UserCreateService = new UserCreateService;
                // ユーザーを追加
                $UserCreateService->createUser($request);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->route('user.index')->with([
            'alert_type' => 'success',
            'alert_message' => 'ユーザーを追加しました。',
        ]);
    }
}