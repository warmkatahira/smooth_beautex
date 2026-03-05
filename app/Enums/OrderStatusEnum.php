<?php

namespace App\Enums;

enum OrderStatusEnum
{
    const KAKUNIN_MACHI         =  1;    // 確認待ち
    const HIKIATE_MACHI         =  2;    // 引当待ち
    const SHUKKA_MACHI          = 10;    // 出荷待ち
    const SAGYO_CHU             = 11;    // 作業中
    const SHUKKA_ZUMI           = 12;    // 出荷済み

    const KAKUNIN_MACHI_JP      = '確認待ち';
    const HIKIATE_MACHI_JP      = '引当待ち';
    const SHUKKA_MACHI_JP       = '出荷待ち';
    const SAGYO_CHU_JP          = '作業中';
    const SHUKKA_ZUMI_JP        = '出荷済み';

    // 表示対象となる注文ステータスを取得
    public static function getOrderMgtDispStatus()
    {
        return [
            self::KAKUNIN_MACHI     => self::KAKUNIN_MACHI_JP,
            self::HIKIATE_MACHI     => self::HIKIATE_MACHI_JP,
            self::SHUKKA_MACHI      => self::SHUKKA_MACHI_JP,
        ];
    }

    // IDから日本語に変換する用の情報を定義
    const CHANGE_LIST_FROM_ID_TO_JP = [
        self::KAKUNIN_MACHI     => self::KAKUNIN_MACHI_JP,
        self::HIKIATE_MACHI     => self::HIKIATE_MACHI_JP,
        self::SHUKKA_MACHI      => self::SHUKKA_MACHI_JP,
        self::SAGYO_CHU         => self::SAGYO_CHU_JP,
        self::SHUKKA_ZUMI       => self::SHUKKA_ZUMI_JP,
    ];

    // IDから日本語に変換
    public static function getJpValueById($id): string
    {
        return self::CHANGE_LIST_FROM_ID_TO_JP[$id] ?? null;
    }
}
