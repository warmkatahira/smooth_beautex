<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    public function messages()
    {
        return [
            'required'                          => ":attributeは必須です。",
            'string'                            => ":attributeは文字列で入力して下さい。",
            'unique'                            => ":attributeは既に使用されています。",
            'image'                             => ":attributeは画像ファイルでなければなりません。",
            'mimes'                             => ":attributeは:values形式のみ許可されています。",
            'boolean'                           => ":attributeが正しくありません。",
            'exists'                            => ":attributeがシステムに存在しません。",
            'integer'                           => ":attributeは数値で入力して下さい。",
            'email'                             => "有効なメールアドレスを入力して下さい。",
            'unique'                            => ":attributeは既に使用されています。",
            'confirmed'                         => ":attributeが確認用と一致しません。",
            'date'                              => ":attributeが日付ではありません。",
            'max'                               => ":attributeは:max文字以内で入力して下さい。",
            'min'                               => ":attributeは:min以上で入力して下さい。",
            'sort_order.max'                    => ":attributeは:max以下で入力して下さい。",
        ];
    }

    public function attributes()
    {
        return [
            // 受注情報
            'order_control_id'          => '受注管理ID',
            'desired_delivery_date'     => '配送希望日',
            'tracking_no'               => '配送伝票番号',
            'order_memo'                => '受注メモ',
            'shipping_work_memo'        => '出荷作業メモ',
            'receipt_name'              => '領収書宛名',
            'shipping_base_id'          => '出荷倉庫',
            'shipping_group_name'       => '出荷グループ名',
            'estimated_shipping_date'   => '出荷予定日',
            'shipping_group_id'         => '出荷グループ',
            // 商品情報
            'item_id'                   => '商品',
            'item_jan_code'             => '商品JANコード',
            'item_name'                 => '商品名',
            'item_category'             => '商品カテゴリ',
            'model_jan_code'            => '代表JANコード',
            'exp_start_position'        => 'EXP開始位置',
            'lot_1_start_position'      => 'LOT1開始位置',
            'lot_1_length'              => 'LOT1桁数',
            'lot_2_start_position'      => 'LOT2開始位置',
            'lot_2_length'              => 'LOT2桁数',
            's_power_code'              => 'S-POWERコード',
            's_power_code_start_position' => 'S-POWERコード開始位置',
            'is_stock_managed'          => '在庫管理',
            // ユーザー情報
            'user_id'                   => 'ユーザーID',
            'last_name'                 => '姓',
            'first_name'                => '名',
            'email'                     => 'メールアドレス',
            'password'                  => 'パスワード',
            'status'                    => 'ステータス',
            // 荷送人
            'shipper_id'                => '荷送人',
            'shipper_company_name'      => '荷送人会社名',
            'shipper_name'              => '荷送人名',
            'shipper_zip_code'          => '荷送人郵便番号',
            'shipper_address'           => '荷送人住所',
            'shipper_tel'               => '荷送人電話番号',
            'shipper_email'             => '荷送人メールアドレス',
            'shipper_invoice_no'        => '荷送人インボイス番号',
            // 自動処理
            'auto_process_id'           => '自動処理',
            'auto_process_name'         => '自動処理名',
            'action_type'               => 'アクション区分',
            'action_column_name'        => 'アクションカラム名',
            'action_value'              => 'アクション値',
            'condition_match_type'      => '条件一致区分',
            'is_active'                 => '有効/無効',
            'column_name.*'             => '条件項目',
            'value.*'                   => '条件値',
            'operator.*'                => '比較方法',
            // その他
            'quantity_threshold'        => '数量閾値',
            'prefecture_id'             => '都道府県',
            'setting_1'                 => '設定1',
            'setting_2'                 => '設定2',
            'setting_3'                 => '設定3',
            'e_hiden_version_id'        => 'e飛伝バージョン',
            'mieru_customer_code'       => 'ミエルカスタマーコード',
            'select_file'               => 'ファイル',
            'is_internal'               => '社内資料',
            // 共通
            'sort_order'                        => '並び順',
            'image_file'                        => '画像',
            'shipping_method_id'                => '配送方法',
            'base_id'                           => '倉庫',
            'base_name'                         => '倉庫名',
            'base_color_code'                   => '倉庫カラー',
            'role_id'                           => '権限',
            'company_id'                        => '会社名',
            'order_category_id'                 => '受注区分',
            'order_category_name'               => '受注区分名',
            'order_item_code'                   => '商品コード',
            'order_item_name'                   => '商品名',
            'order_quantity'                    => '出荷数',
        ];
    }
}