<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseShippingMethod extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'base_shipping_method_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'base_id',
        'shipping_method_id',
        'setting_1',
        'setting_2',
        'setting_3',
        'e_hiden_version_id',
    ];
    // 指定したレコードを取得
    public static function getSpecify($base_shipping_method_id)
    {
        return self::where('base_shipping_method_id', $base_shipping_method_id);
    }
    // 指定したレコードを取得
    public static function getSpecifyByBaseIdAndShippingMethodId($base_id, $shipping_method_id)
    {
        return self::where('base_id', $base_id)->where('shipping_method_id', $shipping_method_id);
    }
    // shipping_methodsテーブルとのリレーション
    public function shipping_method()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id', 'shipping_method_id');
    }
    // basesテーブルとのリレーション
    public function base()
    {
        return $this->belongsTo(Base::class, 'base_id', 'base_id');
    }
    // e_hiden_versionsテーブルとのリレーション
    public function e_hiden_version()
    {
        return $this->belongsTo(EHidenVersion::class, 'e_hiden_version_id', 'e_hiden_version_id');
    }
}
