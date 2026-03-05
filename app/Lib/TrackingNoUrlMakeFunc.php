<?php

namespace App\Lib;

class TrackingNoUrlMakeFunc
{
    // 追跡番号ページURLに配送伝票番号を置換で埋め込む
    public static function make($order)
    {
        // 配送伝票番号をセミコロン「:」でスプリット
        $tracking_no_explode = explode(',', $order->tracking_no);
        // 追跡URLを格納する配列をセット
        $tracking_no_url_arr = [];
        // 配送伝票番号の分だけループ処理
        foreach($tracking_no_explode as $tracking_no){
            // 追跡URLを配列にセット
            $tracking_no_url_arr[$tracking_no] = str_replace('#tracking_no#', $tracking_no, $order->shipping_method->delivery_company->tracking_no_url);
        }
        return $tracking_no_url_arr;
    }
}