<?php

namespace App\Enums;

enum ItemUploadEnum
{
    // 対象
    const UPLOAD_TARGET_ITEM            = 'item';
    const UPLOAD_TARGET_ITEM_JP         = '商品';
    
    // タイプ
    const UPLOAD_TYPE_CREATE            = 'create';
    const UPLOAD_TYPE_CREATE_JP         = '追加';
    const UPLOAD_TYPE_UPDATE            = 'update';
    const UPLOAD_TYPE_UPDATE_JP         = '更新';

    // 配列にした情報を定義
    const UPLOAD_TARGET_LIST = [
        self::UPLOAD_TARGET_ITEM            => self::UPLOAD_TARGET_ITEM_JP,
    ];

    // 配列にした情報を定義
    const UPLOAD_TYPE_LIST = [
        self::UPLOAD_TYPE_CREATE            => self::UPLOAD_TYPE_CREATE_JP,
        self::UPLOAD_TYPE_UPDATE            => self::UPLOAD_TYPE_UPDATE_JP,
    ];

    // 商品追加で必須となるヘッダーを定義
    const REQUIRED_HEADER_ITEM_CREATE = [
        '商品コード',
        '商品JANコード',
        '商品名',
        '商品カテゴリ',
        '代表JANコード',
        'EXP開始位置',
        'LOT1開始位置',
        'LOT1桁数',
        'LOT2開始位置',
        'LOT2桁数',
        'S-POWERコード',
        'S-POWERコード開始位置',
        '在庫管理',
    ];

    // 商品更新で必須となるヘッダーを定義
    const REQUIRED_HEADER_ITEM_UPDATE = [
        '商品コード',
    ];
}