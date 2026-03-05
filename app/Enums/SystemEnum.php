<?php

namespace App\Enums;

enum SystemEnum
{
    // 顧客名
    const CUSTOMER_NAME_JP  = 'BEAUTEX様';
    const CUSTOMER_NAME_EN  = 'beautex';
    // システム名
    const SYSTEM_NAME_JP            = '出荷システム';
    // ページネーションの値を定義
    const PAGINATE_DEFAULT = 1000;
    const PAGINATE_OPERATION_LOG = 200;
    // 初期プロフィール画像のファイル名を定義
    const DEFAULT_PROFILE_IMAGE_FILE_NAME = 'no_image.png';
    // 初期商品画像のファイル名を定義
    const DEFAULT_ITEM_IMAGE_FILE_NAME = 'no_image.png';
    // 初期受注区分画像のファイル名を定義
    const DEFAULT_ORDER_CATEGORY_IMAGE_FILE_NAME = 'no_image.png';
    // 顧客名とシステム名を結合して返す
    public static function getSystemTitle()
    {
        return self::CUSTOMER_NAME_JP . ' ' . self::SYSTEM_NAME_JP;
    }
}