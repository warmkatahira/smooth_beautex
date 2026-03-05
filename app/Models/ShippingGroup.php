<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingGroup extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'shipping_group_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'shipping_group_name',
        'shipping_base_id',
        'estimated_shipping_date',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('shipping_group_id', 'asc');
    }
    // 指定したレコードを取得
    public static function getSpecify($shipping_group_id)
    {
        return self::where('shipping_group_id', $shipping_group_id);
    }
    // ordersテーブルとのリレーション
    public function orders()
    {
        return $this->hasMany(Order::class, 'shipping_group_id', 'shipping_group_id');
    }
    // basesテーブルとのリレーション
    public function base()
    {
        return $this->belongsTo(Base::class, 'shipping_base_id', 'base_id');
    }
}
