<?php

namespace App\Services\SystemAdmin\User;

// モデル
use App\Models\User;
// 列挙
use App\Enums\SystemEnum;
// その他
use Illuminate\Support\Facades\Mail;

class UserUpdateService
{
    // ユーザーを更新
    public function updateUser($request)
    {
        // ユーザーを取得
        $user = User::getSpecify($request->user_no)->first();
        // 値をセット
        $user->last_name = $request->last_name;
        $user->first_name = $request->first_name;
        $user->status = $request->status;
        $user->role_id = $request->role_id;
        $user->company_id = $request->company_id;
        $user->must_change_password = $request->must_change_password;
        // ユーザーを更新
        $user->save();
    }
}