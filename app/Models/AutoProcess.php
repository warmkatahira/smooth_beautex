<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// モデル
use App\Models\ShippingMethod;
use App\Models\Base;
// 列挙
use App\Enums\AutoProcessEnum;
use App\Enums\DeliveryTimeZoneEnum;

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
        return self::where('is_active', true)->orderBy('sort_order', 'asc')->orderBy('auto_process_id', 'asc');
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
        // 配送方法を更新の場合
        if($this->action_type === AutoProcessEnum::SHIPPING_METHOD_UPDATE){
            // 運送会社+配送方法を返す
            return ShippingMethod::getSpecify($this->action_value)->first()->delivery_company_and_shipping_method;
        }
        // 配送希望時間を更新の場合
        if($this->action_type === AutoProcessEnum::DESIRED_DELIVERY_TIME_UPDATE){
            // 配送希望時間を返す
            return DeliveryTimeZoneEnum::TIME_ZONE_LIST[$this->action_value] ?? null;
        }
        // 出荷倉庫を更新の場合
        if($this->action_type === AutoProcessEnum::SHIPPING_BASE_UPDATE){
            // 倉庫を返す
            return Base::getSpecify($this->action_value)->first()->base_name;
        }
        // 注文商品を追加の場合
        if($this->action_type === AutoProcessEnum::ORDER_ITEM_CREATE){
            // 受注商品の情報を返す
            return $this->auto_process_order_item->item->item_jan_code .' / '. $this->auto_process_order_item->item->item_name .' / '. $this->auto_process_order_item->shipping_quantity;
        }
        // そのままの値を返す
        return $this->action_value;
    }
}