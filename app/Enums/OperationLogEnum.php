<?php

namespace App\Enums;

enum OperationLogEnum
{
    // 操作ログの記録を行わないパスを定義
    const NO_OPERATION_RECORD_PATH = [
        // ダッシュボード
        'dashboard',
        'dashboard/ajax_get_chart_data',
        // 受注
        'order_import',
        // 出荷
        'order_document',
        'nifuda_download',
        'shipping_work_end',
        'shipping_work_end',
        'shipping_inspection',
        // 商品
        'item',
        'item_upload',
        'item_qr_analysis',
        // 在庫
        'input_stock_operation',
        'input_stock_operation_enter',
        'stock_history',
        'receiving_inspection',
        'receiving_inspection/ajax_check_lot_exp',
        // 設定
        'auto_process',
        'auto_process_create',
        'auto_process_update',
        'auto_process_condition_update/ajax_validation',
        'auto_process_condition_update',
        'base_shipping_method',
        'base_shipping_method_update',
        'order_category',
        'order_category_update',
        'shipper',
        'shipper_update',
        // システム管理
        'base',
        'base_create',
        'base_update',
        'user',
        'user_create',
        'user_update',
        'operation_log',
        'operation_log_download/download',
        'holiday',
    ];

    // パスの日本語変換用
    const PATH_JP_CHANGE_LIST = [
        // 受注
        'order_import/import'                               => '受注取込',
        'order_mgt'                                         => '受注管理',
        'shipping_work_start/enter'                         => '出荷作業開始',
        'order_detail'                                      => '受注詳細',
        'order_detail_update/shipping_method'               => '配送方法更新',
        'order_import/error_download'                       => '受注取込エラーダウンロード',
        'order_delete/delete'                               => '受注削除',
        'hikiatezan_download/download'                      => '引当残データダウンロード',
        'order_detail_update/supplement'                    => '補足事項更新',
        'order_mgt/allocate'                                => '引当処理',
        // 出荷
        'shipping_mgt'                                      => '出荷管理',
        'total_picking_list_create/create'                  => 'トータルピッキングリスト発行',
        'kobetsu_picking_list_create/create'                => '個別ピッキングリスト発行',
        'nifuda_create/create'                              => '荷札データ作成',
        'nifuda_download/download'                          => '荷札データダウンロード',
        'tracking_no_import/import'                         => '配送伝票番号取込',
        'shipping_inspection/ajax_check_order_control_id'   => '出荷検品(受注管理IDスキャン)',
        'shipping_inspection/ajax_check_tracking_no'        => '出荷検品(配送伝票番号スキャン)',
        'shipping_inspection/ajax_check_item_id_code'       => '出荷検品(商品識別コードスキャン)',
        'shipping_inspection/complete'                      => '出荷検品完了',
        'shipping_history_download/download'                => '出荷履歴ダウンロード',
        'shipping_actual_download/download'                 => '出荷実績ダウンロード',
        'shipping_work_end_history'                         => '出荷完了履歴',
        'shipping_work_end/enter'                           => '出荷完了',
        'shipping_history'                                  => '出荷履歴',
        // 商品
        'item_download/download'                            => '商品ダウンロード',
        'item_upload/upload'                                => '商品アップロード',
        'item_qr_analysis/analysis'                         => '商品QR解析',
        'item_qr_analysis_result_update/update'             => '商品QR解析結果反映',
        // 在庫
        'stock/index_by_item'                               => '在庫(商品別)',
        'stock/index_by_stock'                              => '在庫(在庫別)',
        'input_stock_operation_enter/enter'                 => '入力在庫数操作',
        'stock_history'                                     => '在庫履歴',
        'stock_history_download/download'                   => '在庫履歴ダウンロード',
        'stock_download/download'                           => '在庫ダウンロード',
        'receiving_inspection/ajax_check_item_id_code'      => '入庫検品',
        'receiving_inspection_enter/enter'                  => '入庫確定',
        // 設定
        'auto_process_create/create'                        => '自動処理追加',
        'auto_process_update/update'                        => '自動処理更新',
        'auto_process_condition_update/update'              => '自動処理条件設定',
        'base_shipping_method_update/update'                => '倉庫別配送方法更新',
        // システム管理
        'base_update/update'                                => '倉庫更新',
        'base_create/create'                                => '倉庫追加',
        'user_update/update'                                => 'ユーザー更新',
        'profile'                                           => 'プロフィール',
        'user_create/create'                                => 'ユーザー追加',
        'national_holiday/get_api'                          => '祝日取得',
    ];

    // パスの日本語を取得
    public static function get_path_jp($key): string
    {
        if(array_key_exists($key, self::PATH_JP_CHANGE_LIST)){
            return self::PATH_JP_CHANGE_LIST[$key];
        }else{
            return $key;
        }
    }

    // ダウンロード時のヘッダーを定義
    public static function downloadHeader()
    {
        return [
            '操作日',
            '操作時間',
            'ユーザー名',
            'IPアドレス',
            'メソッド',
            'パス',
            'パラメータ',
        ];
    }
}