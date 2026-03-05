<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// 列挙
use App\Enums\OrderStatusEnum;
use App\Enums\RouteNameEnum;
// その他
use Illuminate\Support\Facades\Route;
use Carbon\CarbonImmutable;

class Order extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'order_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'order_control_id',
        'order_import_date',
        'order_import_time',
        'order_status_id',
        'shipping_method_id',
        'shipping_base_id',
        'shipper_id',
        'desired_delivery_date',
        'desired_delivery_time',
        'is_allocated',
        'is_shipping_inspection_complete',
        'shipping_inspection_date',
        'tracking_no',
        'shipping_date',
        'shipping_group_id',
        'order_no',
        'order_date',
        'order_time',
        'ship_name',
        'ship_zip_code',
        'ship_prefecture_name',
        'ship_address',
        'ship_tel',
        'order_memo',
        'shipping_work_memo',
        'order_category_id',
        'order_mark',
    ];
    // 指定したレコードを取得
    public static function getSpecifyByOrderControlId($order_control_id)
    {
        return self::where('orders.order_control_id', $order_control_id);
    }
    // order_itemsテーブルとのリレーション
    public function order_items()
    {
        return $this->hasMany(OrderItem::class, 'order_control_id', 'order_control_id')
                    ->orderBy('order_items.order_item_code', 'asc');
    }
    // basesテーブルとのリレーション
    public function base()
    {
        return $this->belongsTo(Base::class, 'shipping_base_id', 'base_id');
    }
    // shipping_methodsテーブルとのリレーション
    public function shipping_method()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id', 'shipping_method_id');
    }
    // shippersテーブルとのリレーション
    public function shipper()
    {
        return $this->belongsTo(Shipper::class, 'shipper_id', 'shipper_id');
    }
    // order_categoriesテーブルとのリレーション
    public function order_category()
    {
        return $this->belongsTo(OrderCategory::class, 'order_category_id', 'order_category_id');
    }
    // shipping_groupsテーブルとのリレーション
    public function shipping_group()
    {
        return $this->belongsTo(ShippingGroup::class, 'shipping_group_id', 'shipping_group_id');
    }
    // 運送会社と配送方法を返すアクセサ
    public function getDeliveryCompanyAndShippingMethodAttribute(): string
    {
        return $this->shipping_method->delivery_company->delivery_company . ' ' . $this->shipping_method->shipping_method;
    }
    // 出荷完了対象の受注を取得
    public static function getShippingWorkEndTarget()
    {
        // 注文ステータスが「作業中」かつ、出荷検品が完了している
        return self::where('order_status_id', OrderStatusEnum::SAGYO_CHU)
                ->where('is_shipping_inspection_complete', 1);
    }
    // ダウンロード時のヘッダーを定義
    public static function downloadHeaderAtShippingHistory()
    {
        return [
            '出荷日',
            '取込日',
            '取込時間',
            '注文番号',
            '注文日',
            '受注管理ID',
            '受注区分',
            '出荷倉庫',
            '配送先名',
            '運送会社',
            '配送方法',
            '配送希望日',
            '配送希望時間',
            '配送伝票番号',
            '商品コード',
            '商品JANコード',
            '商品名',
            '出荷数',
        ];
    }
    // ダウンロード時のヘッダーを定義
    public static function downloadHeaderAtShippingActual()
    {
        return [
            'カート番号',
            '配送会社',
            '送り状番号',
            '発送日',
            '決済サイト',
        ];
    }
    // ダウンロード時のヘッダーを定義
    public static function downloadHeaderAtBilling()
    {
        return [
            '出荷日',
            '配送先名',
            '運送会社',
            '配送方法',
            '配送伝票番号',
            '個口数',
            'PCS数',
            '運賃',
        ];
    }
    // 指定した注文ステータスの件数を取得
    public static function getOrderSpecifyOrderStatus($order_status_id)
    {
        return self::where('order_status_id', $order_status_id);
    }
    // 指定した期間の出荷済み件数を取得
    public static function getShippedOrder($from, $to)
    {
       return self::where('order_status_id', OrderStatusEnum::SHUKKA_ZUMI)
                    ->whereDate('shipping_date', '>=', $from)
                    ->whereDate('shipping_date', '<=', $to);
    }
    // 指定した期間の出荷済み数量を取得
    public static function getShippedQuantity($from, $to)
    {
       return self::join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                    ->where('order_status_id', OrderStatusEnum::SHUKKA_ZUMI)
                    ->whereDate('shipping_date', '>=', $from)
                    ->whereDate('shipping_date', '<=', $to)
                    ->selectRaw('SUM(order_items.order_quantity) as total_quantity')
                    ->value('total_quantity');
    }
    // 渡された配列から受注マークの重複を取り除く
    public static function getOrderMarkFilter($array)
    {
        // 空配列を除外
        $filtered = array_filter($array->toArray(), fn($item) => !empty($item));
        // 重複を除く（連想配列の重複除去はちょっと工夫が必要）
        $unique = [];
        foreach($filtered as $item){
            // 連想配列を文字列に変換してユニーク判定
            $key = serialize($item);
            if(!isset($unique[$key])){
                $unique[$key] = $item;
            }
        }
        // 結果は値だけの配列に
        $unique_array = array_values($unique);
        return $unique_array;
    }
}