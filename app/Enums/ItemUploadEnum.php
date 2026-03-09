<?php

namespace App\Enums;

enum ItemUploadEnum
{
    // 商品マスタ
    const PUSH_COLOR_ITEM_MASTER        = 'push_color_item_master';
    const SMOOTH_ITEM_MASTER            = 'smooth_item_master';

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

    // アップロードタイプとファイルタイプによって必須ヘッダーを取得
    public static function get_required_header($upload_type, $file_type){
        // 追加@push colorの場合
        if($upload_type === self::UPLOAD_TYPE_CREATE && $file_type === self::PUSH_COLOR_ITEM_MASTER){
            return [
                '商品コード',
                '商品JANコード',
                '商品名',
                '商品カテゴリ',
                'ブランド',
                '原産国',
                'HSコード',
            ];
        }
        // 更新@push colorの場合
        if($upload_type === self::UPLOAD_TYPE_UPDATE && $file_type === self::PUSH_COLOR_ITEM_MASTER){
            return [
                '商品コード',
                '商品JANコード',
                '商品名',
                '商品カテゴリ',
                'ブランド',
                '原産国',
                'HSコード',
            ];
        }
        // 追加@smoothの場合
        if($upload_type === self::UPLOAD_TYPE_CREATE && $file_type === self::SMOOTH_ITEM_MASTER){
            return [
                '商品コード',
                '商品JANコード',
                '商品名',
                '商品カテゴリ1',
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
        }
        // 更新@smoothの場合
        if($upload_type === self::UPLOAD_TYPE_UPDATE && $file_type === self::SMOOTH_ITEM_MASTER){
            return [
                '商品コード',
                '商品JANコード',
                '商品名',
                '商品カテゴリ1',
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
        }
    }
}