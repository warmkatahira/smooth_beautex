<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// その他
use Illuminate\Support\Facades\Hash;
// リクエスト
use App\Http\Requests\Auth\UserPasswordChangeRequest;

class ChangePasswordController extends Controller
{
    public function showChangeForm()
    {
        return view('auth.passwords.change');
    }

    public function update(UserPasswordChangeRequest $request)
    {
        // ユーザーを取得
        $user = $request->user();
        // 新しいパスワードが現在のパスワードと同じ場合は弾く
        if(Hash::check($request->password, $user->password)){
            return back()->withErrors([
                'password' => '現在のパスワードと同じパスワードは設定できません。',
            ]);
        }
        // パスワードを変更して、フラグを0に変更
        $user->password = Hash::make($request->password);
        $user->must_change_password = 0;
        $user->save();
        return redirect()->route('dashboard.index')->with([
            'alert_type' => 'success',
            'alert_message' => 'パスワードを変更しました。',
        ]);
    }
}