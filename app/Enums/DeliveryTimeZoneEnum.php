<?php

namespace App\Enums;

enum DeliveryTimeZoneEnum
{
    const E_HIDEN_PRO_ID    = 1;
    const E_HIDEN_3_ID      = 2;

    // 配送希望日(日本語)を定義
    const TIME_ZONE_NONE_JP  = '指定なし';
    const TIME_ZONE_AM_JP    = '午前中';
    const TIME_ZONE_1214_JP  = '12時から14時';
    const TIME_ZONE_1416_JP  = '14時から16時';
    const TIME_ZONE_1618_JP  = '16時から18時';
    const TIME_ZONE_1820_JP  = '18時から20時';
    const TIME_ZONE_1921_JP  = '19時から21時';

    // 配送希望日(コード)を定義
    const TIME_ZONE_NONE = 'none';
    const TIME_ZONE_AM   = '01';
    const TIME_ZONE_1214 = '12';
    const TIME_ZONE_1416 = '14';
    const TIME_ZONE_1618 = '16';
    const TIME_ZONE_1820 = '18';
    const TIME_ZONE_1921 = '19';

    // 配送希望日のコードと日本語を配列化
    const TIME_ZONE_LIST = [
        self::TIME_ZONE_NONE    => self::TIME_ZONE_NONE_JP,
        self::TIME_ZONE_AM      => self::TIME_ZONE_AM_JP,
        self::TIME_ZONE_1214    => self::TIME_ZONE_1214_JP,
        self::TIME_ZONE_1416    => self::TIME_ZONE_1416_JP,
        self::TIME_ZONE_1618    => self::TIME_ZONE_1618_JP,
        self::TIME_ZONE_1820    => self::TIME_ZONE_1820_JP,
        self::TIME_ZONE_1921    => self::TIME_ZONE_1921_JP,
    ];

    // 佐川急便の時間帯コード取得用
    const SAGAWA_TIME_ZONE_LIST = [
        self::TIME_ZONE_NONE     => '',
        self::TIME_ZONE_AM       => '01',
        self::TIME_ZONE_1214     => '12',
        self::TIME_ZONE_1416     => '14',
        self::TIME_ZONE_1618     => '16',
        self::TIME_ZONE_1820     => '18',
        self::TIME_ZONE_1921     => '19',
    ];

    // ヤマト運輸の時間帯コード取得用
    const YAMATO_TIME_ZONE_LIST = [
        self::TIME_ZONE_NONE     => '',
        self::TIME_ZONE_AM       => '0812',
        self::TIME_ZONE_1214     => '0812', // ヤマトは12-14がないので、午前中にしている
        self::TIME_ZONE_1416     => '1416',
        self::TIME_ZONE_1618     => '1618',
        self::TIME_ZONE_1820     => '1820',
        self::TIME_ZONE_1921     => '1921',
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
}