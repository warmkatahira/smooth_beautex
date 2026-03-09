<?php

namespace App\Services\Order\OrderImport;

// モデル
use App\Models\OrderCategory;
use App\Models\Prefecture;
use App\Models\OrderImport;
// サービス
use App\Services\Common\ChatworkService;
// 列挙
use App\Enums\OrderStatusEnum;
use App\Enums\ShippingMethodEnum;
// 例外
use App\Exceptions\OrderImportException;
// その他
use Carbon\CarbonImmutable;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderImportForShopifyService
{
    // 注文番号ごとに共通するデータを取得
    public function getCommonOrderValue($save_file_path)
    {
        // データの情報を取得
        $all_line = (new FastExcel)->import($save_file_path);
        // 共通するカラムを定義
        $common_columns = OrderImport::CommonColumns();
        // 注文番号ごとに共通する値を保持する配列を初期化
        $common_order_values = [];
        // データの分だけループ処理
        foreach($all_line as $line){
            // 配送先名が空の場合
            if(empty($line['Shipping Name'])){
                // 次のループ処理へ
                continue;
            }
            // 注文番号を取得
            $order_no = $line['Name'];
            // 注文番号が配列に存在している場合
            if(isset($common_order_values[$order_no])){
                // 次のループ処理へ
                continue;
            }
            // 共通するカラムの分だけループ処理
            foreach($common_columns as $common_column){
                // 配列に格納
                $common_order_values[$order_no][$common_column] = $line[$common_column];
            }
        }
        return $common_order_values;
    }

    // 追加する受注データを配列に格納（同時にバリデーションも実施）
    public function setArrayImport($save_file_path, $nowDate, $order_category_id, $common_order_values)
    {
        // データの情報を取得
        $all_line = (new FastExcel)->import($save_file_path);
        // 追加用の配列をセット
        $create_data = [];
        $validation_error = [];
        // バリデーションエラー出力ファイルのヘッダーを定義
        $validation_error_export_header = array('エラー行数', 'エラー内容');
        // 取得したレコードの分だけループ
        foreach ($all_line as $key => $line){
            // 郵便番号を変数に格納
            $ship_zip_code = $common_order_values[$line['Name']]['Shipping Zip'];
            // 国内発送の時のみ郵便番号の変換処理を実施
            if($common_order_values[$line['Name']]['Shipping Country'] === 'JP'){
                $ship_zip_code = substr(str_replace("-", "", $ship_zip_code), 0, 3).'-'.substr(str_replace("-", "", $ship_zip_code), 3);
            }
            // 追加先テーブルのカラム名に合わせて配列を整理
            $param = [
                'order_import_date'         => $nowDate->toDateString(),
                'order_import_time'         => $nowDate->toTimeString(),
                'order_status_id'           => OrderStatusEnum::KAKUNIN_MACHI,
                'mall_shipping_method'      => $common_order_values[$line['Name']]['Shipping Method'],
                'order_no'                  => $line['Name'],
                'order_date'                => CarbonImmutable::parse($line['Created at'])->toDateString(),
                'order_time'                => CarbonImmutable::parse($line['Created at'])->toTimeString(),
                'ship_name'                 => $common_order_values[$line['Name']]['Shipping Name'],
                'ship_zip_code'             => $ship_zip_code,
                'ship_country_code'         => $common_order_values[$line['Name']]['Shipping Country'],
                'ship_province_code'        => $common_order_values[$line['Name']]['Shipping Province'],
                'ship_province_name'        => $common_order_values[$line['Name']]['Shipping Country'] === 'JP' ? $common_order_values[$line['Name']]['Shipping Province Name'] : '',
                'ship_city'                 => $common_order_values[$line['Name']]['Shipping City'],
                'ship_address_1'            => $common_order_values[$line['Name']]['Shipping Address1'],
                'ship_address_2'            => $common_order_values[$line['Name']]['Shipping Address2'],
                'ship_tel'                  => str_replace(' ', '', str_replace('+81', '0', $common_order_values[$line['Name']]['Shipping Phone'])), // +81を0に置換している
                'order_item_code'           => $line['Lineitem sku'],
                'order_item_name'           => $line['Lineitem name'],
                'shipping_quantity'         => $line['Lineitem quantity'],
                'order_item_unit_price'     => $line['Lineitem price'],
                'unallocated_quantity'      => $line['Lineitem quantity'],
                'order_category_id'         => $order_category_id,
            ];
            // 値が空であれば、nullを格納
            $param = array_map(function ($value){
                return $value === "" ? null : $value;
            }, $param);
            // インポートデータのバリデーション処理
            $message = $this->validation($param, $key + 2);
            // エラーメッセージがある場合
            if(!is_null($message)){
                // バリデーションエラーを配列に格納
                $validation_error[] = array_combine($validation_error_export_header, $message);
            }
            // 追加用の配列に整理した情報を格納
            $create_data[] = $param;
        }
        return compact('create_data', 'validation_error');
    }

    // インポートデータのバリデーション処理
    public function validation($param, $record_num)
    {
        // バリデーションルールを定義
        $rules = [
            'order_no'                  => 'required|max:50',
            'order_date'                => 'required|date',
            'order_time'                => 'required|date_format:H:i:s',
            'order_status_id'           => 'required|in:' . implode(',', array_keys(OrderStatusEnum::CHANGE_LIST_FROM_ID_TO_JP)),
            'mall_shipping_method'      => 'required|string|max:20|in:' . implode(',', ShippingMethodEnum::SHOPIFY_SHIPPING_METHOD_LIST),
            'ship_name'                 => 'required|string|max:255',
            'ship_zip_code'             => 'required|string|max:8',
            'ship_country_code'         => 'required|string|max:5',
            'ship_province_code'        => 'required|string|max:10',
            'ship_province_name'        => 'nullable|string|max:5|exists:prefectures,prefecture_name',
            'ship_city'                 => 'required|string|max:255',
            'ship_address_1'            => 'required|string|max:255',
            'ship_address_2'            => 'nullable|string|max:255',
            'ship_tel'                  => 'required|string|max:30',
            'order_item_code'           => 'required|string|max:255',
            'order_item_name'           => 'required|string|max:255',
            'shipping_quantity'         => 'required|integer|min:1',
            'order_item_unit_price'     => 'required|integer|min:1',
            'unallocated_quantity'      => 'required|integer|min:1',
            'order_category_id'         => 'required|exists:order_categories,order_category_id',
        ];
        // バリデーションエラーメッセージを定義
        $messages = [
            'required'                  => ':attributeは必須です',
            'date'                      => ':attribute（:input）が日付ではありません',
            'date_format'               => ':attribute（:input）が時刻ではありません',
            'max'                       => ':attribute（:input）は:max文字以内にして下さい',
            'min'                       => ':attribute（:input）は:min以上にして下さい',
            'integer'                   => ':attribute（:input）が数値ではありません',
            'exists'                    => ':attribute（:input）がシステム内に存在しません',
            'string'                    => ':attribute（:input）は文字列にして下さい',
            'boolean'                   => ':attribute（:input）が正しくありません',
            'in'                        => ':attribute（:input）がシステム内に存在しません',
        ];
        // バリデーションエラー項目を定義
        $attributes = [
            'order_no'                  => '注文番号',
            'order_date'                => '注文日',
            'order_time'                => '注文時間',
            'order_status_id'           => '注文ステータス',
            'mall_shipping_method'      => '配送方法',
            'ship_name'                 => '配送先名',
            'ship_zip_code'             => '配送先郵便番号',
            'ship_country_code'         => '配送先国コード',
            'ship_province_code'        => '配送先都道府県コード',
            'ship_province_name'        => '配送先都道府県',
            'ship_city'                 => '配送先市区町村',
            'ship_address_1'            => '配送先住所1',
            'ship_address_2'            => '配送先住所2',
            'ship_tel'                  => '配送先電話番号',
            'order_item_code'           => '商品コード',
            'order_item_name'           => '商品名',
            'shipping_quantity'         => '出荷数',
            'order_item_unit_price'     => '商品単価',
            'unallocated_quantity'      => '未引当数',
            'order_category_id'         => '受注区分',
        ];
        // バリデーション実施
        $validator = Validator::make($param, $rules, $messages, $attributes);
        // バリデーションエラーメッセージを格納する変数をセット
        $message = '';
        // バリデーションエラーの分だけループ
        foreach($validator->errors()->toArray() as $errors){
            // メッセージを格納
            $message = empty($message) ? array_shift($errors) : $message . ' / ' . array_shift($errors);
        }
        return empty($message) ? null : array($record_num.'行目', $message);
    }
}