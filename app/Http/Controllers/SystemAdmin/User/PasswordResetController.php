<?php

namespace App\Http\Controllers\SystemAdmin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\SystemAdmin\User\PasswordResetService;
// リクエスト
use App\Http\Requests\SystemAdmin\User\PasswordResetRequest;
// その他
use Illuminate\Support\Facades\DB;

class PasswordResetController extends Controller
{
    public function reset(PasswordResetRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $PasswordResetService = new PasswordResetService;
                // パスワードをリセット
                $PasswordResetService->resetPassword($request->user_no);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->route('user.index')->with([
            'alert_type' => 'success',
            'alert_message' => 'パスワードをリセットしました。',
        ]);
    }
}