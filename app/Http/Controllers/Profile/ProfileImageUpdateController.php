<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Profile\ProfileImageUpdateService;

class ProfileImageUpdateController extends Controller
{
    public function update(Request $request)
    {
        try{
            // インスタンス化
            $ProfileImageUpdateService = new ProfileImageUpdateService;
            // 既存のプロフィール画像を削除
            $user = $ProfileImageUpdateService->deleteCurrentImage();
            // プロフィール画像を保存
            $profile_image_file_name = $ProfileImageUpdateService->saveProfileImage($request, $user);
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => 'プロフィール画像の更新に失敗しました。',
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => 'プロフィール画像を更新しました。',
        ]);
    }
}