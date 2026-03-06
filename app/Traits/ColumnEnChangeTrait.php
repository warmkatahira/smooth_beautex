<?php

namespace App\Traits;

trait ColumnEnChangeTrait
{
    // カラム名を英語に変換
    public static function column_en_change($column): string
    {
        // 配列に定義されている項目であれば、値を返す
        if(array_key_exists($column, static::EN_CHANGE_LIST)){
            return static::EN_CHANGE_LIST[$column];
        }
        // 存在していない場合は、空を返す
        return '';
    }
}