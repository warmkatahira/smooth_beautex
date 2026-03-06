<?php

namespace App\Enums;

enum ShippingMethodEnum
{
    const YAMATO_NEKOPOS_ID = 1;
    const YAMATO_COMPACT_ID = 2;
    const YAMATO_NORMAL_ID  = 3;
    const SAGAWA_NORMAL_ID  = 4;
    const SAGAWA_EMS_ID     = 5;

    // Qoo10の配送会社に入ってくる値を定義
    const QOO10_SHIPPING_METHOD_YAMATO_NEKOPOS  = 'ゆうパケット';
    const QOO10_SHIPPING_METHOD_SAGAWA_NORMAL   = '佐川急便';

    const QOO10_SHIPPING_METHOD_LIST = [
        self::QOO10_SHIPPING_METHOD_YAMATO_NEKOPOS,
        self::QOO10_SHIPPING_METHOD_SAGAWA_NORMAL,
    ];
}