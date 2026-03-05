<?php

namespace App\Http\Controllers\SystemAdmin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\User;
use App\Models\Role;
use App\Models\Company;
// サービス
use App\Services\SystemAdmin\User\UserUpdateService;
// リクエスト
use App\Http\Requests\SystemAdmin\User\UserUpdateRequest;
// その他
use Illuminate\Support\Facades\DB;

class UserUpdateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'ユーザー更新']);
        // ユーザーを取得
        $user = User::getSpecify($request->user_no)->first();
        // 権限を取得
        $roles = Role::getAll()->get();
        // 会社を取得
        $companies = Company::getAll()->get(); 
        return view('system_admin.user.update')->with([
            'user' => $user,
            'roles' => $roles,
            'companies' => $companies,
        ]);
    }

    public function update(UserUpdateRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $UserUpdateService = new UserUpdateService;
                // ユーザーを更新
                $UserUpdateService->updateUser($request);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->route('user.index')->with([
            'alert_type' => 'success',
            'alert_message' => 'ユーザーを更新しました。',
        ]);
    }
}