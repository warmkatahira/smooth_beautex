<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemLot extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'order_item_lot_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'order_item_id',
        'lot',
        'quantity',
    ];
    // 指定したレコードを取得
    public static function getSpecify($order_item_lot_id)
    {
        return self::where('order_item_lot_id', $order_item_lot_id);
    }
    // 指定したレコードを取得
    public static function getSpecifyByOrderItemId($order_item_id)
    {
        return self::where('order_item_id', $order_item_id);
    }
    // order_itemsテーブルとのリレーション
    public function order_item()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id', 'order_item_id');
    }
}
