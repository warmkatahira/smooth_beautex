<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prefecture extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'prefecture_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'prefecture_name',
        'shipping_base_id',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('prefecture_id', 'asc');
    }
    // basesテーブルとのリレーション
    public function base()
    {
        return $this->belongsTo(Base::class, 'shipping_base_id', 'base_id');
    }
    // 住所から都道府県を抽出
    public static function extractPrefecture($address)
    {
        // 静的変数に都道府県一覧をキャッシュするための変数を初期化
        static $prefectures = null;
        // キャッシュが空（初回呼び出し）の場合のみ、DBから都道府県データを取得して格納
        if(is_null($prefectures)){
            $prefectures = self::all();
        }
        // 都道府県の分だけループ処理
        foreach($prefectures as $prefecture){
            // 住所が都道府県名で始まっている場合
            if(str_starts_with($address, $prefecture->prefecture_name)){
                // 都道府県を返す
                return $prefecture->prefecture_name;
            }
        }
        return null;
    }
}
