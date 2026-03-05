<?php

namespace App\Enums;

enum TrackingNoImportEnum
{
    // 佐川急便の配送伝票番号アップロードに必要なカラム名を定義
    const SAGAWA_ORDER_CONTROL_ID = 'お客様管理番号';
    const SAGAWA_TRACKING_NO = 'お問い合せ送り状No.';

    // 佐川急便の配送伝票番号アップロードで必要なカラム名を定義
    const SAGAWA_REQUIRE_HEADER = [
        self::SAGAWA_ORDER_CONTROL_ID,
        self::SAGAWA_TRACKING_NO,
    ];

    // ヤマト運輸の配送伝票番号アップロードに必要なカラム名を定義
    const YAMATO_ORDER_CONTROL_ID = 'お客様管理番号';
    const YAMATO_TRACKING_NO = '伝票番号';

    // ヤマト運輸の配送伝票番号アップロードで必要なカラム名を定義
    const YAMATO_REQUIRE_HEADER = [
        self::YAMATO_ORDER_CONTROL_ID,
        self::YAMATO_TRACKING_NO,
    ];
}