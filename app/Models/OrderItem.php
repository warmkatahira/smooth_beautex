<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// その他
use Illuminate\Database\Eloquent\Casts\Attribute;

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
        'item_id',
        'order_item_code',
        'order_item_name',
        'shipping_quantity',
        'order_item_unit_price',
        'package_no',
        'is_auto_process_add',
        'is_over_threshold',
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
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }
    // 1個口料金オーバーの文字列を返すアクセサ
    public function getIsOverThresholdTextAttribute()
    {
        return $this->is_over_threshold ? '対象' : '対象外';
    }
    // ギフトボックス付き明細か（受注商品名に「＋ギフトボックス」を含み、かつ商品名にDARUMARUを含む）
    protected function isGiftBox(): Attribute
    {
        return Attribute::make(
            get: function () {
                $hasGiftBox = preg_match('/[＋+]\s*ギフトボックス/u', $this->order_item_name ?? '') === 1;
                $isDarumaru = str_contains($this->item?->item_name ?? '', 'DARUMARU');

                return $hasGiftBox && $isDarumaru;
            },
        );
    }
}
