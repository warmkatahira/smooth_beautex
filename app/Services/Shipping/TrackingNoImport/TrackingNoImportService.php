<?php

namespace App\Services\Shipping\TrackingNoImport;

// モデル
use App\Models\Order;
// その他
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
// 列挙
use App\Enums\OrderStatusEnum;
use App\Enums\TrackingNoImportEnum;

class TrackingNoImportService
{
    public function importData($select_file)
    {
        // ストレージに保存する際のファイル名を設定
        $save_file_name = 'tracking_no_import_data.csv';
        // ファイルを保存して保存先のパスを取得
        $save_file_path = Storage::disk('public')->putFileAs('import/', $select_file, $save_file_name);
        // パスを返す
        return Storage::disk('public')->path($save_file_path);
    }

    public function checkHeader($save_file_path)
    {
        // 全データを取得
        $all_line = (new FastExcel)->import($save_file_path);
        // インポートしたデータのヘッダーを取得
        $import_data_header = array_keys(mb_convert_encoding($all_line[0], 'UTF-8', 'ASCII, JIS, UTF-8, SJIS-win'));
        // システムに定義している必須ヘッダーを取得
        $require_header = TrackingNoImportEnum::SAGAWA_REQUIRE_HEADER;
        // ヘッダーが存在するか確認
        $sagawa_header_ng_count = $this->checkRequireHeader($import_data_header, $require_header);
        // 0の場合は、ヘッダーが全て存在するので、ここで処理を終了
        if($sagawa_header_ng_count == 0){
            return with([
                'order_control_id_column' => TrackingNoImportEnum::SAGAWA_ORDER_CONTROL_ID,
                'tracking_no_column' => TrackingNoImportEnum::SAGAWA_TRACKING_NO,
            ]);
        }
        // システムに定義している必須ヘッダーを取得
        $require_header = TrackingNoImportEnum::YAMATO_REQUIRE_HEADER;
        // ヘッダーが存在するか確認
        $yamato_header_ng_count = $this->checkRequireHeader($import_data_header, $require_header);
        // 0の場合は、ヘッダーが全て存在するので、ここで処理を終了
        if($yamato_header_ng_count == 0){
            return with([
                'order_control_id_column' => TrackingNoImportEnum::YAMATO_ORDER_CONTROL_ID,
                'tracking_no_column' => TrackingNoImportEnum::YAMATO_TRACKING_NO,
            ]);
        }
        throw new \RuntimeException('ヘッダーから運送会社が判別できませんでした。');
    }

    // ヘッダーが存在するか確認
    public function checkRequireHeader($import_data_header, $require_header)
    {
        // ヘッダーが存在しなかった場合にカウントする変数をセット
        $header_ng_count = 0;
        // ヘッダーの分だけループ処理
        foreach($require_header as $header){
            // ヘッダーが存在するか確認
            $result = $this->checkValueExists($import_data_header, $header);
            // nullではない場合
            if(!is_null($result)){
                // カウントアップ
                $header_ng_count++;
            }
        }
        return $header_ng_count;
    }

    // 配列の値が存在しているか確認
    public function checkValueExists($array, $value) {
        // 存在したら「true」、存在しなかったら「false」
        $result = in_array($value, $array);
        // 存在しなかったら、エラーを返す
        return !$result ? 'カラムに「'.$value.'」がありません。' : null;
    }

    public function setArrayImportData($save_file_path, $order_control_id_column, $tracking_no_column)
    {
        // データの情報を取得
        $all_line = (new FastExcel)->import($save_file_path);
        // アップロード処理に使用する配列をセット
        $upload_data = [];
        $order_control_id_arr = [];
        $validation_error = [];
        // バリデーションエラー出力ファイルのヘッダーを定義
        $validation_error_export_header = array('エラー行数', 'エラー内容');
        // 取得したレコードの分だけループ
        foreach ($all_line as $key => $line) {
            // UTF-8形式に変換した1行分のデータを取得
            $line = mb_convert_encoding($line, 'UTF-8', 'ASCII, JIS, UTF-8, SJIS-win');
            // 受注管理IDが空欄以外のみ処理する
            if(!empty($line[$order_control_id_column])){
                // 追加先テーブルのカラム名に合わせて配列を整理
                $param = [
                    'order_control_id' => $line[$order_control_id_column],
                    'tracking_no' => empty($line[$tracking_no_column]) ? null : str_replace('-', "",str_replace(array(" ", "　"), "", $line[$tracking_no_column])),
                ];
                // インポートデータのバリデーション処理
                $message = $this->validationImportData($param, $key + 2);
                // エラーメッセージがあればバリデーションエラーを配列に格納
                if (!is_null($message)) {
                    $validation_error[] = array_combine($validation_error_export_header, $message);
                }
                // 追加用の配列に整理した情報を格納
                $upload_data[] = $param;
                // 反映対象の受注管理IDを配列にセット
                $order_control_id_arr[] = $line[$order_control_id_column];
            }
        }
        return compact('upload_data', 'validation_error', 'order_control_id_arr');
    }

    public function validationImportData($param, $record_num)
    {
        // バリデーションルールを定義
        $rules = [
            'order_control_id' => 'required',
            'tracking_no' => 'required|max:14',
        ];
        // バリデーションエラーメッセージを定義
        $messages = [
            'required' => ":attributeは必須です。",
            'max' => ":attributeは:max文字以内で入力して下さい。",
        ];
        // バリデーションエラー項目を定義
        $attributes = [
            'order_control_id' => '受注管理ID',
            'tracking_no' => '配送伝票番号',
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

    // 配送伝票番号反映処理
    public function updateTrackingNo($upload_data, $order_control_id_arr)
    {
        // 反映対象を取得
        $orders = Order::whereIn('order_control_id', $order_control_id_arr)
                    ->where('order_status_id', OrderStatusEnum::SAGYO_CHU)
                    ->lockForUpdate()
                    ->get(['order_control_id']);
        // 反映対象の配送伝票番号をクリア
        Order::whereIn('order_control_id', $orders)->update([
            'tracking_no' => null,
        ]);
        // アップロードデータの分だけループ処理
        foreach($upload_data as $data){
            // 反映対象の受注を取得
            $order = Order::where('order_control_id', $data['order_control_id'])
                        ->where('order_status_id', OrderStatusEnum::SAGYO_CHU)
                        ->first();
            // レコードが取得できている場合
            if(!is_null($order)){
                // 反映処理
                Order::where('order_control_id', $data['order_control_id'])
                    ->where('order_status_id', OrderStatusEnum::SAGYO_CHU)->update([
                    'tracking_no' => $data['tracking_no'],
                ]);
            }
        }
    }
}