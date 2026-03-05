<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'holiday_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'date',
        'name',
        'is_national',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('date', 'asc');
    }
    // 「is_national」に基づいて、文字列を返すアクセサ
    public function getIsNationalTextAttribute(): string
    {
        return $this->is_national ? '○' : '';
    }
}
