<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NifudaCreateHistory extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'nifuda_create_history_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'shipping_group_id',
        'shipping_method_id',
        'directory_name',
        'created_by',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('created_at', 'desc');
    }
    // 指定したレコードを取得
    public static function getSpecify($nifuda_create_history_id)
    {
        return self::where('nifuda_create_history_id', $nifuda_create_history_id);
    }
    // usersテーブルとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_no');
    }
    // shipping_groupsテーブルとのリレーション
    public function shipping_group()
    {
        return $this->belongsTo(ShippingGroup::class, 'shipping_group_id', 'shipping_group_id');
    }
    // shipping_methodsテーブルとのリレーション
    public function shipping_method()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id', 'shipping_method_id');
    }
    // 「last_name」と「first_name」を結合して返すアクセサ
    public function getFullNameAttribute(): string
    {
        return $this->user->last_name . ' ' . $this->user->first_name;
    }
}
