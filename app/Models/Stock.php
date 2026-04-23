<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// 列挙
use App\Enums\RouteNameEnum;

class Stock extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'stock_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'base_id',
        'item_id',
        'lot',
        'exp',
        'total_stock',
        'item_location',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('stock_id', 'asc');
    }
    // 指定したレコードを取得
    public static function getSpecify($stock_id)
    {
        return self::where('stock_id', $stock_id);
    }
    // 指定したレコードを取得
    public static function getSpecifyByItemId($item_id)
    {
        return self::where('item_id', $item_id);
    }
    // 指定したレコードを取得
    public static function getSpecifyByBaseIdItemId($base_id, $item_id)
    {
        return self::where('base_id', $base_id)->where('item_id', $item_id);
    }
    // basesテーブルとのリレーション
    public function base()
    {
        return $this->belongsTo(Base::class, 'base_id', 'base_id');
    }
    // itemsテーブルとのリレーション
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }
    // ダウンロード時のヘッダーを定義
    public static function downloadHeader($route_name, $bases)
    {
        // 商品単位表示の場合
        if($route_name === RouteNameEnum::STOCK_BY_ITEM){
            $header = [
                '商品コード',
                '商品JANコード',
                'カラーID',
                'カラーROW',
                '商品名',
                '商品カテゴリ1',
                '商品カテゴリ2',
                'ブランド',
                '装用期間',
                '入数',
                'メーカー',
                '仕入先',
                '原価単価',
                '在庫管理',
            ];
            // 倉庫の分だけループ処理
            foreach($bases as $base){
                // ヘッダーをセット
                $header[] = $base->base_name.'(在庫数)';
                $header[] = $base->base_name.'(受注数)';
            }
            return $header;
        }
        // 在庫単位表示の場合
        if($route_name === RouteNameEnum::STOCK_BY_STOCK){
            return [
                '倉庫名',
                '商品コード',
                '商品JANコード',
                'カラーID',
                'カラーROW',
                '商品名',
                '商品カテゴリ1',
                '商品カテゴリ2',
                'ブランド',
                '装用期間',
                '入数',
                'メーカー',
                '仕入先',
                '原価単価',
                '商品ロケーション',
                '在庫管理',
                'LOT',
                'EXP',
                '在庫数',
            ];
        }
    }
    // 商品ロケーション更新に必要なヘッダーを定義
    public static function requireHeaderForItemLocationUpdate()
    {
        return [
            '倉庫名',
            '商品コード',
            '商品ロケーション',
        ];
    }
}