<?php

namespace App\Enums;

enum ShipRegionTypeEnum
{
    // 配送地域を定義
    const KOKUNAI   = '国内';
    const KAIGAI    = '海外';

    // 配送地域を配列に格納
    const SHIP_REGION_TYPE_LIST = [
        self::KOKUNAI   => self::KOKUNAI,
        self::KAIGAI    => self::KAIGAI,
    ];
}