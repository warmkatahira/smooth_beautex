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

    // 都道府県名のローマ字→漢字変換マップ(Shopify用)
    public const PROVINCE_NAME_MAP = [
        'Aichi'     => '愛知県',
        'Akita'     => '秋田県',
        'Aomori'    => '青森県',
        'Chiba'     => '千葉県',
        'Ehime'     => '愛媛県',
        'Fukui'     => '福井県',
        'Fukuoka'   => '福岡県',
        'Fukushima' => '福島県',
        'Gifu'      => '岐阜県',
        'Gunma'     => '群馬県',
        'Hiroshima' => '広島県',
        'Hokkaido'  => '北海道',
        'Hyogo'     => '兵庫県',
        'Ibaraki'   => '茨城県',
        'Ishikawa'  => '石川県',
        'Iwate'     => '岩手県',
        'Kagawa'    => '香川県',
        'Kagoshima' => '鹿児島県',
        'Kanagawa'  => '神奈川県',
        'Kochi'     => '高知県',
        'Kumamoto'  => '熊本県',
        'Kyoto'     => '京都府',
        'Mie'       => '三重県',
        'Miyagi'    => '宮城県',
        'Miyazaki'  => '宮崎県',
        'Nagano'    => '長野県',
        'Nagasaki'  => '長崎県',
        'Nara'      => '奈良県',
        'Niigata'   => '新潟県',
        'Oita'      => '大分県',
        'Okayama'   => '岡山県',
        'Okinawa'   => '沖縄県',
        'Osaka'     => '大阪府',
        'Saga'      => '佐賀県',
        'Saitama'   => '埼玉県',
        'Shiga'     => '滋賀県',
        'Shimane'   => '島根県',
        'Shizuoka'  => '静岡県',
        'Tochigi'   => '栃木県',
        'Tokushima' => '徳島県',
        'Tottori'   => '鳥取県',
        'Toyama'    => '富山県',
        'Tokyo'     => '東京都',
        'Yamagata'  => '山形県',
        'Yamaguchi' => '山口県',
        'Yamanashi' => '山梨県',
        'Wakayama'  => '和歌山県',
    ];

    // 都道府県名をローマ字から漢字へ変換（マップに無ければそのまま返す）
    public static function convertProvinceName(?string $province_name): ?string
    {
        if($province_name === null || $province_name === ''){
            return $province_name;
        }
        // ローマ字に一致したときだけ変換。最初から漢字ならそのまま返る
        return self::PROVINCE_NAME_MAP[$province_name] ?? $province_name;
    }
}
