<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'order_item_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'order_control_id',
        'is_item_allocated',
        'is_stock_allocated',
        'unallocated_quantity',
        'order_item_code',
        'order_item_name',
        'order_quantity',
        'is_auto_process_add',
    ];
    // 指定したレコードを取得
    public static function getSpecify($order_item_id)
    {
        return self::where('order_item_id', $order_item_id);
    }
    // 指定したレコードを取得
    public static function getSpecifyByOrderControlId($order_control_id)
    {
        return self::where('order_control_id', $order_control_id);
    }
    // ordersテーブルとのリレーション
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_control_id', 'order_control_id');
    }
    // order_item_lotsテーブルとのリレーション
    public function order_item_lots()
    {
        return $this->hasMany(OrderItemLot::class, 'order_item_id', 'order_item_id');
    }
    // itemsテーブルとのリレーション
    public function item()
    {
        return $this->belongsTo(Item::class, 'order_item_code', 'item_code');
    }
}
