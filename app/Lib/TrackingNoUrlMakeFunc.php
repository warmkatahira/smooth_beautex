<?php

namespace App\Lib;

// 列挙
use App\Enums\ShippingMethodEnum;

class TrackingNoUrlMakeFunc
{
    // 追跡番号ページURLに配送伝票番号を置換で埋め込む
    public static function make($order)
    {
        // 配送伝票番号をセミコロン「,」でスプリット
        $tracking_no_explode = explode(',', $order->tracking_no);
        // 佐川EMSの場合は日本郵便の一括追跡URLを生成
        if($order->shipping_method?->shipping_method_id === ShippingMethodEnum::SAGAWA_EMS_ID){
            return self::makeJapanPostUrl($tracking_no_explode);
        }
        // 追跡URLを格納する配列をセット
        $tracking_no_url_arr = [];
        // 配送伝票番号の分だけループ処理
        foreach($tracking_no_explode as $tracking_no){
            // 追跡URLを配列にセット
            $tracking_no_url_arr[$tracking_no] = str_replace('#tracking_no#', $tracking_no, $order->shipping_method?->delivery_company->tracking_no_url);
        }
        return $tracking_no_url_arr;
    }

    // 日本郵便の一括追跡URL（最大10件）を生成
    private static function makeJapanPostUrl(array $tracking_nos): array
    {
        $params = [];
        for($i = 1; $i <= 10; $i++){
            $params['requestNo' . $i] = isset($tracking_nos[$i - 1]) ? trim($tracking_nos[$i - 1]) : '';
        }
        $params['search.x']          = 90;
        $params['search.y']          = 32;
        $params['startingUrlPatten'] = '';
        $params['locale']            = 'ja';
        $url = 'https://trackings.post.japanpost.jp/services/srv/search?' . http_build_query($params);
        // 追跡番号ごとにキーを分けて、URLは同じものを返す
        $url_arr = [];
        foreach($tracking_nos as $tracking_no){
            $url_arr[trim($tracking_no)] = $url;
        }
        return $url_arr;
    }
}