<?php

namespace App\Services\Profile;

// モデル
use App\Models\User;
// 列挙
use App\Enums\SystemEnum;
// その他
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class ProfileImageUpdateService
{
    // 既存のプロフィール画像を削除
    public function deleteCurrentImage()
    {
        // ユーザーを取得
        $user = User::getSpecify(Auth::user()->user_no)->first();
        // 現在設定されているプロフィール画像ファイル名を取得
        $profile_image_path = storage_path('app/public/profile_images/' . $user->profile_image_file_name);
        // 現在設定されているプロフィール画像が存在しているかつ、初期画像以外なら削除
        if(file_exists($profile_image_path) && $user->profile_image_file_name != SystemEnum::DEFAULT_PROFILE_IMAGE_FILE_NAME){
            unlink($profile_image_path);
        }
        return $user;
    }

    // プロフィール画像を保存
    public function saveProfileImage($request, $user)
    {
        // オリジナル画像を取得
        $image = $request->file('image');
        // 画像の拡張子を取得（例: jpg, png）
        $extension = $image->getClientOriginalExtension();
        // 希望するドライバーで新しいマネージャーインスタンスを作成
        $manager = new ImageManager(new Driver());
        // Intervention Imageで画像を読み込む
        $img = $manager->read($image->getRealPath());
        // トリミング座標を取得
        $x = intval($request->crop_data_x);
        $y = intval($request->crop_data_y);
        $width = intval($request->crop_data_width);
        $height = intval($request->crop_data_height);
        // 画像をトリミング
        $img->crop($width, $height, $x, $y);
        // 保存するファイル名を設定（uuid + 拡張子）
        $profile_image_file_name = (string) Str::uuid() . '.' . $extension;
        // 保存するパスを設定
        $profile_image_path = storage_path('app/public/profile_images/' . $profile_image_file_name);
        // トリミングした画像を保存
        $img->save($profile_image_path);
        // プロフィール画像ファイル名を更新
        $user->update([
            'profile_image_file_name' => $profile_image_file_name,
        ]);
        return;
    }
}