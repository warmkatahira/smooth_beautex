<?php

namespace App\Services\SystemAdmin\User;

// モデル
use App\Models\User;
// 列挙
use App\Enums\SystemEnum;
// その他
use App\Mail\UserAvailableMail;
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
        // ステータスが0から1に変更された場合
        if($user->isDirty('status') && $user->status == 1 && $user->getOriginal('status') == 0){
            // ユーザーに承認メールを送信
            $this->sendMail($request);
        }
        // ユーザーを更新
        $user->save();
        return;
    }

    // ユーザーに承認メールを送信
    public function sendMail($request)
    {
        // +-+-+-+-+-+-+-+-+-+-   アカウント承認完了メール通知   +-+-+-+-+-+-+-+-+-+-
        // 承認したユーザーを取得
        $user = User::getSpecify($request->user_no);
        // インスタンス化
        $mail = new UserAvailableMail($user->first());
        // Toを設定
        $mail->to($user->pluck('email')->toArray());
        // 件名を設定
        $mail->subject('【smooth_'.SystemEnum::CUSTOMER_NAME.'】アカウント承認完了通知');
        // メールを送信
        Mail::send($mail);
        // +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
        return;
    }
}