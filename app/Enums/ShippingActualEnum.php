<?php

namespace App\Enums;

// 列挙
use App\Enums\ShippingMethodEnum;

enum ShippingActualEnum
{
    // Qoo10の出荷実績データのヘッダーを定義
    const QOO10_HEADER = [
        '配送状態',
        '注文番号',
        'カート番号',
        '配送会社',
        '送り状番号',
        '発送日',
        '注文日',
        '入金日',
        'お届け希望日',
        '発送予定日',
        '配送完了日',
        '配送方法',
        '商品番号',
        '商品名',
        '数量',
        'オプション情報',
        'オプションコード',
        'おまけ',
        '受取人名',
        '受取人名(フリガナ)',
        '受取人電話番号',
        '受取人携帯電話番号',
        '住所',
        '郵便番号',
        '国家',
        '送料の決済',
        '決済サイト',
        '通貨',
        '購入者決済金額',
        '販売価格',
        '割引額',
        '注文金額の合計',
        '供給原価の合計',
        '購入者名',
        '購入者名(フリガナ)',
        '配送要請事項',
        '購入者電話番号',
        '購入者携帯電話番号',
        '販売者商品コード',
        'JANコード',
        '規格番号',
        'プレゼント贈り主',
        '外部広告',
        '素材',
        'ギフト注文',
    ];

    // shopifyの出荷実績データのヘッダーを定義
    const SHOPIFY_HEADER = [
        'Name',
        'Command',
        'Line: Type',
        'Fulfillment: Status',
        'Fulfillment: Tracking Company',
        'Fulfillment: Tracking Number',
        'Fulfillment: Send Receipt',
    ];

    // Qoo10の「配送会社」を取得する処理
    public static function qoo10_shipping_method_get($shipping_method_id)
    {
        // 配送方法IDによって返す値を可変
        return match($shipping_method_id) {
            ShippingMethodEnum::YAMATO_NEKOPOS_ID   => 'ネコポス',
            ShippingMethodEnum::SAGAWA_NORMAL_ID    => '佐川急便',
            default                                 => $shipping_method_id,
        };
    }

    // shopifyの「配送会社」を取得する処理
    public static function shopify_shipping_method_get($shipping_method_id)
    {
        // 配送方法IDによって返す値を可変
        return match($shipping_method_id) {
            ShippingMethodEnum::YAMATO_NEKOPOS_ID   => 'ヤマト運輸',
            ShippingMethodEnum::SAGAWA_NORMAL_ID    => '佐川急便',
            ShippingMethodEnum::SAGAWA_EMS_ID       => 'Japan Post',
            default                                 => $shipping_method_id,
        };
    }
}
