<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'base_id';
    // オートインクリメント無効化
    public $incrementing = false;
    // 操作可能なカラムを定義
    protected $fillable = [
        'base_id',
        'base_name',
        'base_color_code',
        'mieru_customer_code',
        'sort_order',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('sort_order', 'asc');
    }
    // 指定したレコードを取得
    public static function getSpecify($base_id)
    {
        return self::where('base_id', $base_id);
    }
    // stocksテーブルとのリレーション
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'base_id', 'base_id');
    }
    // base_shipping_methodsテーブルとのリレーション
    public function base_shipping_methods()
    {
        return $this->hasMany(BaseShippingMethod::class, 'base_id', 'base_id');
    }
    // 倉庫名から倉庫IDを取得
    public static function getBaseIdByBaseName($base_name)
    {
        // 倉庫名から倉庫IDを取得
        $base_id = self::where('base_name', $base_name)->value('base_id');
        // 存在していない場合は、渡された値を返す
        return $base_id ?? $base_name;
    }
}
