<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'shipping_method_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'shipping_method',
        'delivery_company_id',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('shipping_method_id', 'asc');
    }
    // delivery_companiesテーブルとのリレーション
    public function delivery_company()
    {
        return $this->belongsTo(DeliveryCompany::class, 'delivery_company_id', 'delivery_company_id');
    }
    // 指定したレコードを取得
    public static function getSpecify($shipping_method_id)
    {
        return self::where('shipping_method_id', $shipping_method_id);
    }
    // 運送会社と配送方法を返すアクセサ
    public function getDeliveryCompanyAndShippingMethodAttribute(): string
    {
        return $this->delivery_company->delivery_company . ' ' . $this->shipping_method;
    }
}
