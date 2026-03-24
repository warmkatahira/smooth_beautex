<?php

namespace App\Enums;

enum TrackingNoImportEnum
{
    // 国内用
    // 佐川急便の配送伝票番号アップロードに必要なカラム名を定義
    const SAGAWA_JP_ORDER_CONTROL_ID   = 'お客様管理番号';
    const SAGAWA_JP_TRACKING_NO        = 'お問い合せ送り状No.';

    // 佐川急便の配送伝票番号アップロードで必要なカラム名を定義
    const SAGAWA_JP_REQUIRE_HEADER = [
        self::SAGAWA_JP_ORDER_CONTROL_ID,
        self::SAGAWA_JP_TRACKING_NO,
    ];

    // 海外用
    // 佐川急便の配送伝票番号アップロードに必要なカラム名を定義
    const SAGAWA_GLOBAL_ORDER_CONTROL_ID   = 'メモ';
    const SAGAWA_GLOBAL_TRACKING_NO        = '追跡番号';

    // 佐川急便の配送伝票番号アップロードで必要なカラム名を定義
    const SAGAWA_GLOBAL_REQUIRE_HEADER = [
        self::SAGAWA_GLOBAL_ORDER_CONTROL_ID,
        self::SAGAWA_GLOBAL_TRACKING_NO,
    ];

    // ヤマト運輸の配送伝票番号アップロードに必要なカラム名を定義
    const YAMATO_ORDER_CONTROL_ID   = 'お客様管理番号';
    const YAMATO_TRACKING_NO        = '伝票番号';

    // ヤマト運輸の配送伝票番号アップロードで必要なカラム名を定義
    const YAMATO_REQUIRE_HEADER = [
        self::YAMATO_ORDER_CONTROL_ID,
        self::YAMATO_TRACKING_NO,
    ];
}