<?php

namespace App\Enums;

enum AutoProcessEnum
{
    // 比較演算子を定義
    const EQUAL         = '=';              // 等しい
    const NOT_EQUAL     = '!=';             // 等しくない
    const GREATER       = '>';              // より大きい
    const GREATER_EQUAL = '>=';             // 以上
    const LESS          = '<';              // より小さい
    const LESS_EQUAL    = '<=';             // 以下
    const CONTAINS      = 'contains';       // 含む（部分一致など）
    const NOT_CONTAINS  = 'not contains';   // 含まない（部分一致など）
    const IS_NULL       = 'is null';        // Nullである
    const IS_NOT_NULL   = 'is not null';    // Nullではない

    // 比較演算子を日本語の文字列と配列化
    const OPERATOR_LIST = [
        self::EQUAL         => '等しい',
        self::NOT_EQUAL     => '等しくない',
        self::GREATER       => 'より大きい',
        self::GREATER_EQUAL => '以上',
        self::LESS          => 'より小さい',
        self::LESS_EQUAL    => '以下',
        self::CONTAINS      => '含む',
        self::NOT_CONTAINS  => '含まない',
        self::IS_NULL       => '空欄である',    // Nullをあえて空欄に言い換えている
        self::IS_NOT_NULL   => '空欄ではない',  // Nullをあえて空欄に言い換えている
    ];

    // アクション区分を定義
    const SHIPPING_METHOD_CHANGE    = 'shipping_method_change';     // 配送方法を変更
    const ORDER_MARK_UPDATE         = 'order_mark_update';          // 受注マークを更新
    const SHIPPING_WORK_MEMO_UPDATE = 'shipping_work_memo_update';  // 出荷作業メモを更新
    const ORDER_ITEM_CREATE         = 'order_item_create';          // 受注商品を追加

    // アクション区分を日本語の文字列と配列化
    const ACTION_TYPE_LIST = [
        self::SHIPPING_METHOD_CHANGE    => '配送方法を変更',
        self::ORDER_MARK_UPDATE         => '受注マークを更新',
        self::SHIPPING_WORK_MEMO_UPDATE => '出荷作業メモを更新',
        self::ORDER_ITEM_CREATE         => '受注商品を追加',
    ];

    // アクション区分の日本語文字列を返す関数
    public static function getActionTypeJP(string $action_type)
    {
        return self::ACTION_TYPE_LIST[$action_type] ?? null;
    }

    // アクション区分に対応するカラム名を配列化
    const ACTION_TYPE_COLUMN_MAPPING = [
        self::SHIPPING_METHOD_CHANGE    => self::SHIPPING_METHOD_ID,
        self::ORDER_MARK_UPDATE         => self::ORDER_MARK,
        self::SHIPPING_WORK_MEMO_UPDATE => self::SHIPPING_WORK_MEMO,
    ];

    // アクション区分から対応するカラム名を返す関数
    public static function getActionTypeColumnName(string $action_type)
    {
        return self::ACTION_TYPE_COLUMN_MAPPING[$action_type] ?? null;
    }

    // テーブル名を定義
    const ORDERS        = 'orders';
    const ORDER_ITEMS   = 'order_items';

    // カラム名を定義
    const ORDER_NO                  = 'order_no';                   // 注文番号
    const ORDER_DATE                = 'order_date';                 // 注文日
    const ORDER_TIME                = 'order_time';                 // 注文時間
    const SHIPPING_METHOD_ID        = 'shipping_method_id';         // 配送方法
    const SHIPPING_WORK_MEMO        = 'shipping_work_memo';         // 出荷作業メモ
    const SHIP_PREFECTURE_NAME      = 'ship_prefecture_name';       // 配送先都道府県
    const SHIP_NAME                 = 'ship_name';                  // 配送先名
    const ORDER_MARK                = 'order_mark';                 // 受注マーク
    const ORDER_ITEM_CODE           = 'order_item_code';            // 受注商品コード
    const ORDER_ITEM_NAME           = 'order_item_name';            // 受注商品名
    const ORDER_QUANTITY            = 'order_quantity';             // 出荷数

    // カラム名を日本語の文字列と配列化
    const COLUMN_NAME_LIST = [
        self::ORDER_NO             => '注文番号',
        self::ORDER_DATE           => '注文日',
        self::ORDER_TIME           => '注文時間',
        self::SHIPPING_METHOD_ID   => '配送方法',
        self::SHIPPING_WORK_MEMO   => '出荷作業メモ',
        self::SHIP_PREFECTURE_NAME => '配送先都道府県',
        self::SHIP_NAME            => '配送先名',
        self::ORDER_MARK           => '受注マーク',
        self::ORDER_ITEM_CODE      => '受注商品コード',
        self::ORDER_ITEM_NAME      => '受注商品名',
        self::ORDER_QUANTITY       => '出荷数',
    ];

    // テーブルとカラムをマッピング
    const TABLE_MAPPING = [
        self::ORDER_NO             => 'orders',
        self::ORDER_DATE           => 'orders',
        self::ORDER_TIME           => 'orders',
        self::SHIPPING_METHOD_ID   => 'orders',
        self::SHIPPING_WORK_MEMO   => 'orders',
        self::SHIP_PREFECTURE_NAME => 'orders',
        self::SHIP_NAME            => 'orders',
        self::ORDER_MARK           => 'orders',
        self::ORDER_ITEM_CODE      => 'order_items',
        self::ORDER_ITEM_NAME      => 'order_items',
        self::ORDER_QUANTITY       => 'order_items',
    ];

    public static function checkCondition($order, $auto_process_condition)
    {
        // 受注から条件で設定されているカラムの値を取得
        $order_value = data_get($order, $auto_process_condition->column_name);
        // 条件で設定されている条件値を取得
        $condition_value = $auto_process_condition->value;
        // 条件の比較演算子によって分岐処理
        switch($auto_process_condition->operator){
            case self::EQUAL:
                return $order_value == $condition_value;
            case self::NOT_EQUAL:
                return $order_value != $condition_value;
            case self::GREATER:
                return $order_value > $condition_value;
            case self::GREATER_EQUAL:
                return $order_value >= $condition_value;
            case self::LESS:
                return $order_value < $condition_value;
            case self::LESS_EQUAL:
                return $order_value <= $condition_value;
            case self::CONTAINS:
                return str_contains($order_value, $condition_value);
            case self::NOT_CONTAINS:
                return !str_contains($order_value, $condition_value);
            case self::IS_NULL:
                return is_null($order_value);
            case self::IS_NOT_NULL:
                return !is_null($order_value);
        }
        // どの条件にも該当しなかった場合はfalseを返す
        return false;
    }

    // 条件一致区分を定義
    const ALL   = 'all';    // 全て満たす
    const ANY   = 'any';    // いずれかを満たす

    // 条件一致区分を日本語の文字列と配列化
    const CONDITION_MATCH_TYPE_LIST = [
        self::ALL   => '全てを満たす',
        self::ANY   => 'いずれかを満たす',
    ];

    // 条件一致区分の日本語文字列を返す関数
    public static function getConditionMatchTypeJP(string $condition_match_type)
    {
        return self::CONDITION_MATCH_TYPE_LIST[$condition_match_type] ?? null;
    }
}
