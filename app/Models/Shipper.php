<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipper extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'shipper_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'shipper_name',
        'shipper_zip_code',
        'shipper_address',
        'shipper_tel',
        'shipper_email',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('shipper_id', 'asc');
    }
    // 指定したレコードを取得
    public static function getSpecify($shipper_id)
    {
        return self::where('shipper_id', $shipper_id);
    }
}
