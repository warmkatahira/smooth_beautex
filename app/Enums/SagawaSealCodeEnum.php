<?php

namespace App\Enums;

// 列挙
use App\Enums\DeliveryTimeZoneEnum;

enum SagawaSealCodeEnum
{
    // e飛伝の情報を定義
    const E_HIDEN_PRO_ID    = 1;
    const E_HIDEN_3_ID      = 2;

    // 佐川急便の日時指定のシールコードを取得
    public static function sagawa_seal_code_get($e_hiden_version, $desired_delivery_date, $desired_delivery_time)
    {
        // 配送希望時間が埋まっている場合
        if(!is_null($desired_delivery_time) && $desired_delivery_time != DeliveryTimeZoneEnum::TIME_ZONE_NONE){
            // e飛伝Proの場合
            if(self::E_HIDEN_PRO_ID === $e_hiden_version->e_hiden_version_id){
                return '007';
            }
            // e飛伝3の場合
            if(self::E_HIDEN_3_ID === $e_hiden_version->e_hiden_version_id){
                // 一致するコードを取得
                return match($desired_delivery_time) {
                    DeliveryTimeZoneEnum::TIME_ZONE_AM       => '020',
                    DeliveryTimeZoneEnum::TIME_ZONE_1214     => '022',
                    DeliveryTimeZoneEnum::TIME_ZONE_1416     => '023',
                    DeliveryTimeZoneEnum::TIME_ZONE_1618     => '024',
                    DeliveryTimeZoneEnum::TIME_ZONE_1820     => '025',
                    DeliveryTimeZoneEnum::TIME_ZONE_1921     => '026',
                    default             => $desired_delivery_time, // 存在しない場合のデフォルト値
                };
            }
        }
        // 配送希望日が埋まっている場合
        if(!is_null($desired_delivery_date)){
            return '005';
        }
        return '';
    }
}