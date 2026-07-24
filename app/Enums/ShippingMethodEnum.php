<?php

namespace App\Enums;

enum ShippingMethodEnum
{
    const YAMATO_NEKOPOS_ID = 1;    // ヤマト運輸 ネコポス
    const YAMATO_COMPACT_ID = 2;    // ヤマト運輸 コンパクト
    const YAMATO_NORMAL_ID  = 3;    // ヤマト運輸 宅急便
    const SAGAWA_NORMAL_ID  = 4;    // 佐川急便 宅配便
    const SAGAWA_EMS_ID     = 5;    // 佐川急便 EMS
    const UPS_ID            = 6;    // UPS UPS
    const DHL_ID            = 7;    // DHL DHL

    // Qoo10の配送会社に入ってくる値を定義
    const QOO10_SHIPPING_METHOD_YAMATO_NEKOPOS  = 'ゆうパケット';
    const QOO10_SHIPPING_METHOD_SAGAWA_NORMAL   = '佐川急便';

    const QOO10_SHIPPING_METHOD_LIST = [
        self::QOO10_SHIPPING_METHOD_YAMATO_NEKOPOS,
        self::QOO10_SHIPPING_METHOD_SAGAWA_NORMAL,
    ];

    // shopifyの配送会社に入ってくる値を定義
    const SHOPIFY_SHIPPING_METHOD_SE        = 'Standard EMS';
    const SHOPIFY_SHIPPING_METHOD_SS        = 'Standard Shipping';
    const SHOPIFY_SHIPPING_METHOD_UPS       = 'UPS ( Fastest )';
    const SHOPIFY_SHIPPING_METHOD_RESHIP    = 'ReShip';
    const SHOPIFY_SHIPPING_METHOD_NORMAL    = '通常配送';
    const SHOPIFY_SHIPPING_METHOD_MAIL      = 'メール便';
    const SHOPIFY_SHIPPING_METHOD_TAKKYU    = '宅急便';

    const SHOPIFY_SHIPPING_METHOD_LIST = [
        self::SHOPIFY_SHIPPING_METHOD_SE,
        self::SHOPIFY_SHIPPING_METHOD_SS,
        self::SHOPIFY_SHIPPING_METHOD_UPS,
        self::SHOPIFY_SHIPPING_METHOD_RESHIP,
        self::SHOPIFY_SHIPPING_METHOD_NORMAL,
        self::SHOPIFY_SHIPPING_METHOD_MAIL,
        self::SHOPIFY_SHIPPING_METHOD_TAKKYU,
    ];
}