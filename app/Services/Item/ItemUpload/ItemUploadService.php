<?php

namespace App\Services\Item\ItemUpload;

// モデル
use App\Models\Item;
use App\Models\ItemImport;
// 列挙
use App\Enums\ItemUploadEnum;
// その他
use Carbon\CarbonImmutable;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemUploadService
{
    // 選択したデータをストレージにインポート
    public function importData($select_file)
    {
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 選択したデータのファイル名を取得
        $upload_original_file_name = $select_file->getClientOriginalName();
        // ストレージに保存する際のファイル名を設定
        $save_file_name = 'item_upload_data_'.$nowDate->format('Y-m-d H-i-s').'.csv';
        // ファイルを保存して保存先のパスを取得
        $path = Storage::disk('public')->putFileAs('upload/item_upload', $select_file, $save_file_name);
        // フルパスに調整する
        return with([
            'upload_original_file_name' => $upload_original_file_name,
            'save_file_full_path' => Storage::disk('public')->path($path),
        ]);
    }

    // インポートしたデータのヘッダーを確認
    public function checkHeader($save_file_full_path, $upload_type)
    {
        // 全データを取得
        $all_line = (new FastExcel)->import($save_file_full_path);
        // インポートしたデータのヘッダーを取得
        $data_header = array_keys(mb_convert_encoding($all_line[0], 'UTF-8', 'ASCII, JIS, UTF-8, SJIS-win'));
        // タイプ=追加の場合
        if($upload_type === ItemUploadEnum::UPLOAD_TYPE_CREATE){
            // チェックする項目を配列に格納
            $check_column = ItemUploadEnum::REQUIRED_HEADER_ITEM_CREATE;
        }
        // タイプ=更新の場合
        if($upload_type === ItemUploadEnum::UPLOAD_TYPE_UPDATE){
            // チェックする項目を配列に格納
            $check_column = ItemUploadEnum::REQUIRED_HEADER_ITEM_UPDATE;
        }
        // チェックするカラムの分だけループ処理
        foreach($check_column as $column){
            // カラムが存在するか確認
            $result = $this->checkValueExists($data_header, $column);
            // nullでなければエラーを返す
            if(!is_null($result)){
                return $result;
            }
        }
        return null;
    }

    // 配列の値が存在しているか確認
    public function checkValueExists($array, $value){
        // 存在したら「true」、存在しなかったら「false」
        $result = in_array($value, $array);
        // 存在しなかったら、エラーを返す
        return !$result ? 'カラムに「'.$value.'」がありません。' : null;
    }
}