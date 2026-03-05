<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// 通知
use App\Notifications\UserResetPasswordNotification;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // 主キーカラムを変更
    protected $primaryKey = 'user_no';
    // 操作可能なカラムを定義
    protected $fillable = [
        'user_id',
        'last_name',
        'first_name',
        'email',
        'password',
        'status',
        'role_id',
        'company_id',
        'profile_image_file_name',
        'last_login_at',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('user_no', 'asc');
    }
    // 指定したレコードを取得
    public static function getSpecify($user_no)
    {
        return self::where('user_no', $user_no);
    }
    // rolesテーブルとのリレーション
    public function role()
    {
        return $this->belongsTo(Role::Class, 'role_id', 'role_id');
    }
    // companiesテーブルとのリレーション
    public function company()
    {
        return $this->belongsTo(Company::Class, 'company_id', 'company_id');
    }
    // 「last_name」と「first_name」を結合して返すアクセサ
    public function getFullNameAttribute(): string
    {
        return $this->last_name . ' ' . $this->first_name;
    }
    // 「status」に基づいて、有効 or 無効を返すアクセサ
    public function getStatusTextAttribute(): string
    {
        return $this->status ? '有効' : '無効';
    }
    // パスワードリセットの通知をカスタマイズ
    public function sendPasswordResetNotification($token)
    {
        $url = url("reset-password/{$token}");
        $this->notify(new UserResetPasswordNotification($url));
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
