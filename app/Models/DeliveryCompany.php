<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryCompany extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'delivery_company_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'delivery_company',
        'tracking_no_url',
        'company_image',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('delivery_company_id', 'asc');
    }
    // shipping_methodsテーブルとのリレーション
    public function shipping_methods()
    {
        return $this->hasMany(ShippingMethod::class, 'delivery_company_id', 'delivery_company_id');
    }

    // 運送会社+配送方法を取得
    public static function getShippingMethodArr()
    {
        // 運送会社+配送方法を格納する配列を初期化
        $shipping_method_arr = [];
        // 運送会社を取得
        $delivery_companies = self::getAll()->with('shipping_methods')->get();
        // 運送会社の分だけループ処理
        foreach($delivery_companies as $delivery_company){
            // 配送方法の分だけループ処理
            foreach($delivery_company->shipping_methods as $shipping_method){
                // 運送会社+配送方法を配列に格納（キーは配送方法ID）
                $shipping_method_arr[$shipping_method->shipping_method_id] = $delivery_company->delivery_company . ' ' . $shipping_method->shipping_method;
            }
        }
        return $shipping_method_arr;
    }
}
