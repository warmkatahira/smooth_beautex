<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'item_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'item_code',
        'item_jan_code',
        'item_name',
        'item_category',
        'model_jan_code',
        'exp_start_position',
        'lot_1_start_position',
        'lot_1_length',
        'lot_2_start_position',
        'lot_2_length',
        's_power_code',
        's_power_code_start_position',
        'is_stock_managed',
        'item_image_file_name',
        'sort_order',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('sort_order', 'asc');
    }
    // 指定したレコードを取得
    public static function getSpecify($item_id)
    {
        return self::where('item_id', $item_id);
    }
    // 指定したレコードを取得
    public static function getSpecifyByItemCode($item_code)
    {
        return self::where('item_code', $item_code);
    }
    // stocksテーブルとのリレーション
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'item_id', 'item_id');
    }
    // order_itemsとのリレーション
    public function order_items()
    {
        return $this->hasMany(OrderItem::class, 'order_item_code', 'item_code');
    }
    // shipping_methodsテーブルとのリレーション
    public function shipping_method()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id', 'shipping_method_id');
    }
    // ダウンロード時のヘッダーを定義
    public static function downloadHeader()
    {
        return [
            '商品コード',
            '商品JANコード',
            '商品名',
            '商品カテゴリ',
            '代表JANコード',
            'EXP開始位置',
            'LOT1開始位置',
            'LOT1桁数',
            'LOT2開始位置',
            'LOT2桁数',
            'S-POWERコード',
            'S-POWERコード開始位置',
            '在庫管理',
            '並び順',
            '商品画像',
            '最終更新日時',
        ];
    }
    // 英語カラム変換用
    const EN_CHANGE_LIST = [
        '商品コード'            => 'item_code',
        '商品JANコード'         => 'item_jan_code',
        '商品名'                => 'item_name',
        '商品カテゴリ'          => 'item_category',
        '代表JANコード'        => 'model_jan_code',
        'EXP開始位置'          => 'exp_start_position',
        'LOT1開始位置'        => 'lot_1_start_position',
        'LOT1桁数'             => 'lot_1_length',
        'LOT2開始位置'         => 'lot_2_start_position',
        'LOT2桁数'             => 'lot_2_length',
        'S-POWERコード'        => 's_power_code',
        'S-POWERコード開始位置'  => 's_power_code_start_position',
        '在庫管理'              => 'is_stock_managed',
        '並び順'                => 'sort_order',
    ];
    // カラム名を英語に変換
    public static function column_en_change($column): string
    {
        // 定義されている項目であれば、値を返す
        if(array_key_exists($column, self::EN_CHANGE_LIST)){
            return self::EN_CHANGE_LIST[$column];
        }
        // 存在していない場合は、空を返す
        return '';
    }
    // 運送会社と配送方法を返すアクセサ
    public function getDeliveryCompanyAndShippingMethodAttribute(): string
    {
        return $this->shipping_method->delivery_company->delivery_company . ' ' . $this->shipping_method->shipping_method;
    }
    // 「is_stock_managed」に基づいて、有効 or 無効を返すアクセサ
    public function getIsStockManagedTextAttribute(): string
    {
        return $this->is_stock_managed ? '有効' : '無効';
    }
    // 商品コードから商品IDを取得
    public static function getItemIdByItemCode($item_code)
    {
        // 商品コードから商品IDを取得
        $item_id = self::where('item_code', $item_code)->value('item_id');
        // 存在していない場合は、渡された値を返す
        return $item_id ?? $item_code;
    }
}
