<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// 列挙
use App\Enums\OrderCategoryEnum;

class OrderImport extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'order_import_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'order_control_id',
        'order_import_date',
        'order_import_time',
        'order_status_id',
        'shipping_method',
        'shipping_base_id',
        'desired_delivery_date',
        'desired_delivery_time',
        'order_no',
        'order_date',
        'order_time',
        'ship_name',
        'ship_zip_code',
        'ship_country_code',
        'ship_province_code',
        'ship_province_name',
        'ship_city',
        'ship_address_1',
        'ship_address_2',
        'ship_tel',
        'order_item_code',
        'order_item_name',
        'shipping_quantity',
        'unallocated_quantity',
        'order_category_id',
    ];
    // 指定したレコードを取得
    public static function getSpecifyByOrderNo($order_no)
    {
        return self::where('order_no', $order_no);
    }
    // 受注インポートに必要なヘッダーを定義
    public static function requireHeaderForOrderImport($order_category_id)
    {
        // Qoo10の場合
        if($order_category_id === OrderCategoryEnum::QOO10_ID){
            return [
                'カート番号',
                '配送会社',
                '注文日',
                '商品名',
                '数量',
                'オプションコード',
                '受取人名',
                '受取人電話番号',
                '受取人携帯電話番号',
                '住所',
                '郵便番号',
            ];
        }
        // shopifyの場合
        if($order_category_id === OrderCategoryEnum::SHOPIFY_ID){
            return [
                'Name',
                'Shipping Method',
                'Lineitem quantity',
                'Lineitem name',
                'Lineitem sku',
                'Shipping Name',
                'Shipping Address1',
                'Shipping Address2',
                'Shipping City',
                'Shipping Zip',
                'Shipping Province',
                'Shipping Country',
                'Shipping Phone',
            ];
        }
    }
    // ordersテーブルに追加する情報を取得
    public static function createTargetListForOrder($query)
    {
        return $query->select([
            'order_control_id',
            'order_import_date',
            'order_import_time',
            'order_status_id',
            'shipping_method',
            'shipping_base_id',
            'desired_delivery_date',
            'desired_delivery_time',
            'order_no',
            'order_date',
            'order_time',
            'ship_name',
            'ship_zip_code',
            'ship_country_code',
            'ship_province_code',
            'ship_province_name',
            'ship_city',
            'ship_address_1',
            'ship_address_2',
            'ship_tel',
            'order_category_id',
        ]);
    }
    // order_itemsテーブルに追加する情報を取得
    public static function createTargetListForOrderItem($query)
    {
        return $query->select([
            'order_control_id',
            'unallocated_quantity',
            'order_item_code',
            'order_item_name',
            'shipping_quantity',
        ]);
    }
}