<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// モデル
use App\Models\ShippingMethod;
// 列挙
use App\Enums\AutoProcessEnum;

class AutoProcess extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'auto_process_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'auto_process_name',
        'action_type',
        'action_column_name',
        'action_value',
        'condition_match_type',
        'is_active',
        'sort_order',
    ];
    // auto_process_order_itemsテーブルとのリレーション
    public function auto_process_order_item()
    {
        return $this->hasOne(AutoProcessOrderItem::class, 'auto_process_id', 'auto_process_id');
    }
    // auto_process_conditionsテーブルとのリレーション
    public function auto_process_conditions()
    {
        return $this->hasMany(AutoProcessCondition::class, 'auto_process_id', 'auto_process_id');
    }
    // is_activeが「1」(有効)の自動処理を取得
    public static function getIsActive()
    {
        return self::where('is_active', 1)->orderBy('sort_order', 'asc')->orderBy('auto_process_id', 'asc');
    }
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('sort_order', 'asc')->orderBy('auto_process_id', 'asc');
    }
    // 指定したレコードを取得
    public static function getSpecify($auto_process_id)
    {
        return self::where('auto_process_id', $auto_process_id);
    }
    // 「is_active」によって有効/無効を返すアクセサ
    public function getIsActiveTextAttribute(): string
    {
        return $this->is_active ? '有効' : '無効';
    }
    // 「action_type」によって「action_value」を変換して返すアクセサ
    public function getActionValueTextAttribute(): string
    {
        // 配送方法を変更の場合
        if($this->action_type === AutoProcessEnum::SHIPPING_METHOD_CHANGE){
            // 運送会社+配送方法を返す
            return ShippingMethod::getSpecify($this->action_value)->first()->delivery_company_and_shipping_method;
        }
        // 受注商品を追加の場合
        if($this->action_type === AutoProcessEnum::ORDER_ITEM_CREATE){
            // 受注商品の情報を返す
            return $this->auto_process_order_item->order_item_code.' / '.$this->auto_process_order_item->order_item_name.' / '.$this->auto_process_order_item->order_quantity;
        }
        // 配送方法を変更以外の場合
        if($this->action_type !== AutoProcessEnum::SHIPPING_METHOD_CHANGE){
            // そのままの値を返す
            return $this->action_value;
        }
    }
}
