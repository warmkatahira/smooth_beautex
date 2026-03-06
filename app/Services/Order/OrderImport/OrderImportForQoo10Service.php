<?php

namespace App\Services\Order\OrderImport;

// モデル
use App\Models\Prefecture;
use App\Models\OrderImport;
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

class OrderImportForQoo10Service
{
    // 追加する受注データを配列に格納（同時にバリデーションも実施）
    public function setArrayImport($save_file_path, $nowDate, $order_category_id)
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
            // 郵便番号をハイフンつきにして変数に格納
            $ship_zip_code = substr(str_replace("-", "", $line['郵便番号']), 0, 3).'-'.substr(str_replace("-", "", $line['郵便番号']), 3);
            // 追加先テーブルのカラム名に合わせて配列を整理
            $param = [
                'order_import_date'         => $nowDate->toDateString(),
                'order_import_time'         => $nowDate->toTimeString(),
                'order_status_id'           => OrderStatusEnum::KAKUNIN_MACHI,
                'shipping_method'           => $line['配送会社'],
                'order_no'                  => $line['カート番号'],
                'order_date'                => CarbonImmutable::parse($line['注文日'])->toDateString(),
                'order_time'                => CarbonImmutable::parse($line['注文日'])->toTimeString(),
                'ship_name'                 => $line['受取人名'],
                'ship_zip_code'             => $ship_zip_code,
                'ship_province_name'        => Prefecture::extractPrefecture($line['住所']),
                'ship_address_1'            => $line['住所'],
                'ship_tel'                  => $line['受取人携帯電話番号'] != '-' ? $line['受取人携帯電話番号'] : $line['受取人電話番号'], // 携帯電話番号がなければ、電話番号を適用
                'order_item_code'           => $line['オプションコード'],
                'order_item_name'           => $line['商品名'],
                'order_quantity'            => $line['数量'],
                'unallocated_quantity'      => $line['数量'],
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
            'shipping_method'           => 'required|string|max:20|in:' . implode(',', ShippingMethodEnum::QOO10_SHIPPING_METHOD_LIST),
            'ship_name'                 => 'required|string|max:255',
            'ship_zip_code'             => 'required|string|max:8',
            'ship_province_name'        => 'required|string|max:5|exists:prefectures,prefecture_name',
            'ship_address_1'            => 'required|string|max:255',
            'ship_tel'                  => 'required|string|max:30',
            'order_item_code'           => 'required|string|max:255',
            'order_item_name'           => 'required|string|max:255',
            'order_quantity'            => 'required|integer|min:1',
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
            'shipping_method'           => '配送方法',
            'ship_name'                 => '配送先名',
            'ship_zip_code'             => '配送先郵便番号',
            'ship_province_name'        => '配送先都道府県',
            'ship_address_1'            => '配送先住所1',
            'ship_tel'                  => '配送先電話番号',
            'order_item_code'           => '商品コード',
            'order_item_name'           => '商品名',
            'order_quantity'            => '出荷数',
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

    // 「配送会社」を注文番号毎で1つになるように更新
    // 1つの注文番号で配送方法が混ざってくる場合があるので、注文番号で1つに揃える
    // 上位（大きいサイズ）に合わせる
    public function updateShippingMethod()
    {
        // 注文番号と配送方法の種類数を取得(種類数が2以上の注文のみ取得)
        $orders = OrderImport::select(
                        'order_control_id',
                        DB::raw('COUNT(DISTINCT shipping_method) as shipping_method_count'),
                    )
                    ->groupBy('order_control_id')
                    ->having('shipping_method_count', '>=', 2)
                    ->get();
        // 複数の配送方法がある注文がない場合
        if($orders->isEmpty()){
            // 処理を抜ける
            return;
        }
        // 対象の注文番号を配列に格納する
        $order_control_ids = $orders->pluck('order_control_id');
        // 配送方法を佐川急便に更新
        OrderImport::whereIn('order_control_id', $order_control_ids)
                    ->update([
                        'shipping_method' => '佐川急便'
                    ]);
    }
}