<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mall extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'mall_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'mall_name',
        'mall_image_file_name',
        'sort_order',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('sort_order', 'asc');
    }
}
