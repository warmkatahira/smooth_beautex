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
            // 郵便番号を変数に格納
            $ship_zip_code = substr(str_replace("-", "", $line['郵便番号']), 0, 3).'-'.substr(str_replace("-", "", $line['郵便番号']), 3);
            // 追加先テーブルのカラム名に合わせて配列を整理
            $param = [
                'order_import_date'         => $nowDate->toDateString(),
                'order_import_time'         => $nowDate->toTimeString(),
                'order_status_id'           => OrderStatusEnum::KAKUNIN_MACHI,
                'shipping_method_id'        => ShippingMethodEnum::YAMATO_NEKOPOS_ID,
                'order_no'                  => $line['カート番号'],
                'order_date'                => CarbonImmutable::parse($line['注文日'])->toDateString(),
                'order_time'                => CarbonImmutable::parse($line['注文日'])->toTimeString(),
                'ship_name'                 => $line['受取人名'],
                'ship_zip_code'             => $ship_zip_code,
                'ship_prefecture_name'      => Prefecture::extractPrefecture($line['住所']),
                'ship_address'              => $line['住所'],
                'ship_tel'                  => $line['受取人携帯電話番号'] != '-' ? $line['受取人携帯電話番号'] : $line['受取人電話番号'],
                'order_item_code'           => $line['オプションコード'],
                'order_item_name'           => $line['商品名'],
                'order_quantity'            => $line['数量'],
                'unallocated_quantity'      => $line['数量'],
                'shipper_id'                => OrderCategory::getSpecify($order_category_id)->first()->shipper_id,
                'order_category_id'         => $order_category_id,
                'seller_item_code'          => $line['販売者商品コード'],
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
            'shipping_method_id'        => 'required|exists:shipping_methods,shipping_method_id',
            'order_no'                  => 'required|max:50',
            'order_date'                => 'required|date',
            'order_time'                => 'required|date_format:H:i:s',
            'ship_name'                 => 'required|string|max:255',
            'ship_zip_code'             => 'required|string|max:8',
            'ship_prefecture_name'      => 'required|exists:prefectures,prefecture_name',
            'ship_address'              => 'required|string|max:255',
            'ship_tel'                  => 'required|string|max:15',
            'order_item_code'           => 'required|string|max:255',
            'order_item_name'           => 'required|string|max:255',
            'order_quantity'            => 'required|integer|min:1',
            'unallocated_quantity'      => 'required|integer|min:1',
            'shipper_id'                => 'required|exists:shippers,shipper_id',
            'order_category_id'         => 'required|exists:order_categories,order_category_id',
            'seller_item_code'          => 'required|string|max:50',
        ];
        // バリデーションエラーメッセージを定義
        $messages = [
            'required'                  => ':attributeは必須です',
            'order_date.date'           => ':attribute（:input）が日付ではありません',
            'order_time.date'           => ':attribute（:input）が日付ではありません',
            'max'                       => ':attribute（:input）は:max文字以内にして下さい',
            'min'                       => ':attribute（:input）は:min以上にして下さい',
            'integer'                   => ':attribute（:input）が数値ではありません',
            'exists'                    => ':attribute（:input）がシステム内に存在しません',
            'string'                    => ':attribute（:input）は文字列にして下さい',
            'boolean'                   => ':attribute（:input）が正しくありません',     
        ];
        // バリデーションエラー項目を定義
        $attributes = [
            'shipping_method_id'        => '配送方法ID',
            'order_no'                  => '注文番号',
            'order_date'                => '注文日',
            'order_time'                => '注文時間',
            'ship_name'                 => '配送先名',
            'ship_zip_code'             => '配送先郵便番号',
            'ship_prefecture_name'      => '配送先都道府県',
            'ship_address'              => '配送先住所',
            'ship_tel'                  => '配送先電話番号',
            'order_item_code'           => '商品コード',
            'order_item_name'           => '商品名',
            'order_quantity'            => '出荷数',
            'unallocated_quantity'      => '未引当数',
            'shipper_id'                => '荷送人',
            'order_category_id'         => '受注区分',
            'seller_item_code'          => '販売者商品コード',
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

    // order_item_codeを更新
    public function updateOrderItemCode()
    {
        // order_importsテーブルのレコード分だけループ処理
        foreach(OrderImport::all() as $order){
            // 商品コードを格納する配列を初期化
            $order_item_code_arr = [];
            // order_item_codeを「,」でスプリット
            $order_item_code_explode = explode(',', $order->order_item_code);
            // 要素の分だけループ処理
            // スプリットした要素をカンマで結合していく（1つ目と2つ目を結合、3つ目と4つ目を結合・・・・）
            for($i = 0; $i < count($order_item_code_explode); $i += 2){
                // 結合する要素を変数に格納
                $first  = $order_item_code_explode[$i];
                $second = $order_item_code_explode[$i + 1] ?? null;
                // 2つ目がある場合
                if($second !== null){
                    // 結合して配列に格納
                    $order_item_code_arr[] = $first . ',' . $second;
                // 2つ目がない場合
                }else{
                    // 余りが出た場合はそのまま配列に格納
                    $order_item_code_arr[] = $first;
                }
            }
            // 商品コードの分だけループ処理
            foreach($order_item_code_arr as $order_item_code){
                // レコードを複製
                $clone = $order->replicate();
                // 複製したレコードを更新
                $clone->order_item_code = $order_item_code;
                // 複製したレコードを保存
                $clone->save();
            }
            // clone元のレコードを削除
            $order->delete();
        }
    }

    // 購入数を更新
    public function updateOrderQuantity()
    {
        // 対象の販売者商品コードを配列に格納
        $target_codes = ['econ-1d-20-6s'];
        // 配列の中にある販売者商品コードの場合、購入数を×2する
        OrderImport::whereIn('seller_item_code', $target_codes)
            ->update([
                'order_quantity'        => DB::raw('order_quantity * 2'),
                'unallocated_quantity'  => DB::raw('unallocated_quantity * 2'),
            ]);
        // 購入数を更新する対象が存在する場合
        if(OrderImport::whereIn('seller_item_code', $target_codes)->get()->isNotEmpty()){
            // インスタンス化
            $ChatworkService = new ChatworkService;
            // Chatworkに通知する処理
            $ChatworkService->postMessage();
        }
    }
}