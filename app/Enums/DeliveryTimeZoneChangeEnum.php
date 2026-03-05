<?php

namespace App\Enums;

enum DeliveryTimeZoneChangeEnum
{
    const E_HIDEN_PRO_ID    = 1;
    const E_HIDEN_3_ID      = 2;

    const ZONE_NONE = '指定なし';
    const ZONE_AM   = '午前中（12時まで）';
    const ZONE_1214 = '12時から14時';
    const ZONE_1416 = '14時から16時';
    const ZONE_1618 = '16時から18時';
    const ZONE_1820 = '18時から20時';
    const ZONE_1921 = '19時から21時';

    // 佐川急便の時間帯コード取得用
    const SAGAWA_TIME_ZONE_LIST = [
        self::ZONE_NONE     => '',
        self::ZONE_AM       => '01',
        self::ZONE_1214     => '12',
        self::ZONE_1416     => '14',
        self::ZONE_1618     => '16',
        self::ZONE_1820     => '18',
        self::ZONE_1921     => '19',
    ];

    // ヤマト運輸の時間帯コード取得用
    const YAMATO_TIME_ZONE_LIST = [
        self::ZONE_NONE     => '',
        self::ZONE_AM       => '0812',
        self::ZONE_1214     => '0812', // ヤマトは12-14がないので、午前中にしている
        self::ZONE_1416     => '1416',
        self::ZONE_1618     => '1618',
        self::ZONE_1820     => '1820',
        self::ZONE_1921     => '1921',
    ];

    // DBの設定値から佐川急便の時間帯コードを取得
    public static function sagawa_time_zone_get($key): string
    {
        // nullの場合は空文字を返す
        if(is_null($key)){
            return '';
        }
        return self::SAGAWA_TIME_ZONE_LIST[$key] ?? $key;
    }

    // DBの設定値からヤマト運輸の時間帯コードを取得
    public static function yamato_time_zone_get($key): string
    {
        // nullの場合は空文字を返す
        if(is_null($key)){
            return '';
        }
        return self::YAMATO_TIME_ZONE_LIST[$key] ?? $key;
    }

    // 佐川急便の日時指定のシールコードを取得
    public static function sagawa_seal_code_get($e_hiden_version, $desired_delivery_date, $desired_delivery_time)
    {
        // 希望時間が埋まっている場合
        if(!is_null($desired_delivery_time) && $desired_delivery_time != self::ZONE_NONE){
            // e飛伝Proの場合
            if(self::E_HIDEN_PRO_ID === $e_hiden_version->e_hiden_version_id){
                return '007';
            }
            // e飛伝3の場合
            if(self::E_HIDEN_3_ID === $e_hiden_version->e_hiden_version_id){
                // 一致するコードを取得
                return match($desired_delivery_time) {
                    self::ZONE_AM       => '020',
                    self::ZONE_1214     => '022',
                    self::ZONE_1416     => '023',
                    self::ZONE_1618     => '024',
                    self::ZONE_1820     => '025',
                    self::ZONE_1921     => '026',
                    default             => $desired_delivery_time, // 存在しない場合のデフォルト値
                };
            }
        }
        // 希望日が埋まっている場合
        if(!is_null($desired_delivery_date)){
            return '005';
        }
        return '';
    }
}