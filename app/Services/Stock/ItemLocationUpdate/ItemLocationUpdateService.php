<?php

namespace App\Services\Stock\ItemLocationUpdate;

// モデル
use App\Models\Item;
use App\Models\Base;
use App\Models\Stock;
// その他
use Carbon\CarbonImmutable;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ItemLocationUpdateService
{
    // 選択したデータをストレージにインポート
    public function importData($select_file)
    {
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // ストレージに保存する際のファイル名を設定
        $save_file_name = 'item_location_update_data_'.$nowDate->format('Y-m-d H-i-s').'.csv';
        // ファイルを保存して保存先のパスを取得
        $save_file_path = Storage::disk('public')->putFileAs('import', $select_file, $save_file_name);
        // 保存先のパスがNullの場合
        if(is_null($save_file_path)){
            throw new \RuntimeException('データが認識できませんでした。');
        }
        // フルパスに調整する
        return Storage::disk('public')->path($save_file_path);
    }

    // インポートしたデータのヘッダーを確認
    public function checkHeader($save_file_path)
    {
        // 全データを取得
        $all_line = (new FastExcel)->import($save_file_path);
        // データが空の場合の処理
        if($all_line->isEmpty()){
            throw new \RuntimeException('データがありませんでした。');
        }
        // インポートしたデータのヘッダーを取得
        $import_data_header = array_keys(mb_convert_encoding($all_line[0], 'UTF-8', 'ASCII, JIS, UTF-8, SJIS-win'));
        // システムに定義している必須ヘッダーを取得
        $require_header = Stock::requireHeaderForItemLocationUpdate();
        // ヘッダーの分だけループ処理
        foreach($require_header as $header){
            // ヘッダーが存在するか確認
            $result = $this->checkValueExists($import_data_header, $header);
            // nullではない場合
            if(!is_null($result)){
                throw new \RuntimeException('ファイルが正しくない為、取り込みできませんでした。');
            }
        }
    }

    // 配列の値が存在しているか確認
    public function checkValueExists($array, $value){
        // 存在したら「true」、存在しなかったら「false」
        $result = in_array($value, $array);
        // 存在しなかったら、エラーを返す
        return !$result ? 'カラムに「'.$value.'」がありません。' : null;
    }

    // 追加する受注データを配列に格納（同時にバリデーションも実施）
    public function setArrayImport($save_file_path)
    {
        // データの情報を取得
        $all_line = (new FastExcel)->import($save_file_path);
        // 更新用の配列をセット
        $update_data = [];
        $validation_error = [];
        // バリデーションエラー出力ファイルのヘッダーを定義
        $validation_error_export_header = array('エラー行数', 'エラー内容');
        // 取得したレコードの分だけループ
        foreach ($all_line as $key => $line){
            // UTF-8形式に変換した1行分のデータを取得
            $line = mb_convert_encoding($line, 'UTF-8', 'ASCII, JIS, UTF-8, SJIS-win');
            // 追加先テーブルのカラム名に合わせて配列を整理
            $param = [
                'base_id'           => Base::getBaseIdByBaseName($line['倉庫名']),
                'item_id'           => Item::getItemIdByItemCode($line['商品コード']),
                'item_location'     => empty($line['商品ロケーション']) ? null : $line['商品ロケーション'],
            ];
            // インポートデータのバリデーション処理
            $message = $this->validation($param, $key + 2);
            // エラーメッセージがある場合
            if(!is_null($message)){
                // バリデーションエラーを配列に格納
                $validation_error[] = array_combine($validation_error_export_header, $message);
            }
            // 追加用の配列に整理した情報を格納
            $update_data[] = $param;
        }
        // バリデーションエラーがある場合
        if(count(array_filter($validation_error)) != 0){
            throw new \RuntimeException('データが正しくない為、取り込みできませんでした。');
        }
        return $update_data;
    }

    // インポートデータのバリデーション処理
    public function validation($param, $record_num)
    {
        // バリデーションルールを定義
        $rules = [
            'base_id'           => 'required|exists:bases,base_id',
            'item_id'           => 'required|exists:items,item_id',
            'item_location'     => 'nullable|string|max:20',
        ];
        // バリデーションエラーメッセージを定義
        $messages = [
            'required'              => ':attributeは必須です',
            'max'                   => ':attribute（:input）は:max文字以内にして下さい',
            'exists'                => ':attribute（:input）がシステム内に存在しません',
            'string'                => ':attribute（:input）は文字列にして下さい',
        ];
        // バリデーションエラー項目を定義
        $attributes = [
            'base_id'       => '倉庫名',
            'item_id'       => '商品コード',
            'item_location' => '商品ロケーション',
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

    // 商品ロケーションを更新
    public function updateItemLocation($update_data)
    {
        // 一時テーブルを作成
        DB::statement("
            CREATE TEMPORARY TABLE temp_stocks (
                base_id VARCHAR(10) NOT NULL,
                item_id INT UNSIGNED NOT NULL,
                item_location VARCHAR(20) NULL
            )
        ");
        // 一時テーブルへレコードを追加
        foreach($update_data as $data){
            DB::table('temp_stocks')->insert($data);
        }
        // 取り込まれた商品ロケーションをstocksに反映
        DB::statement("
            UPDATE stocks
            JOIN temp_stocks ON stocks.base_id = temp_stocks.base_id
                            AND stocks.item_id = temp_stocks.item_id
            SET stocks.item_location = temp_stocks.item_location
        ");
    }
}