<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCategory extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'order_category_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'order_category_name',
        'order_category_image_file_name',
        'shipper_id',
        'sort_order',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('sort_order', 'asc');
    }
    // 指定したレコードを取得
    public static function getSpecify($order_category_id)
    {
        return self::where('order_category_id', $order_category_id);
    }
    // shippersテーブルとのリレーション
    public function shipper()
    {
        return $this->belongsTo(Shipper::class, 'shipper_id', 'shipper_id');
    }
}
