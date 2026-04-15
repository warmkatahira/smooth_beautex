<?php

namespace App\Services\SystemAdmin\User;

// モデル
use App\Models\User;
// 列挙
use App\Enums\SystemEnum;
// その他
use App\Mail\PasswordResetNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PasswordResetService
{
    // パスワードをリセット
    public function resetPassword($user_no)
    {
        // リセットするログインパスワードを取得（英数字12桁）
        $password = Str::random(12);
        // ユーザーを取得
        $user = User::getSpecify($user_no)->lockForUpdate()->first();
        // パスワードを更新し、パスワード変更を必須にする
        $user->update([
            'password'                  => Hash::make($password),
            'is_must_change_password'   => 1,
        ]);
        // アカウント発行通知メールを送信
        $this->sendMail($user, $password);
    }

    // パスワード変更通知メールを送信
    public function sendMail($user, $password)
    {
        // +-+-+-+-+-+-+-+-+-+-   パスワードリセット通知メール   +-+-+-+-+-+-+-+-+-+-
        // インスタンス化
        $mail = new PasswordResetNotificationMail($user, $password);
        // Toを設定
        $mail->to(Auth::user()->email);
        // 件名を設定
        $mail->subject('【'.SystemEnum::getSystemTitle().'】パスワードリセット通知');
        // メールを送信
        Mail::send($mail);
        // +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
    }
}