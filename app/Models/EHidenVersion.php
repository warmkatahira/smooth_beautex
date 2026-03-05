<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EHidenVersion extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'e_hiden_version_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'e_hiden_version',
        'file_name',
        'file_extension',
        'data_start_row',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('e_hiden_version_id', 'asc');
    }
}
