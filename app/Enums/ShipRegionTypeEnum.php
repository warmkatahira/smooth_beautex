<?php

namespace App\Enums;

enum ShipRegionTypeEnum
{
    // 出荷先を定義
    const KOKUNAI   = '国内';
    const KAIGAI    = '海外';

    // 出荷先を配列に格納
    const SHIP_REGION_TYPE_LIST = [
        self::KOKUNAI   => self::KOKUNAI,
        self::KAIGAI    => self::KAIGAI,
    ];
}