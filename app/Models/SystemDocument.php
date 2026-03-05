<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemDocument extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'system_document_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'file_name',
        'sort_order',
        'is_internal',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('sort_order', 'asc');
    }
    // 指定したレコードを取得
    public static function getSpecify($system_document_id)
    {
        return self::where('system_document_id', $system_document_id);
    }
}
