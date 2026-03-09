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
        // アップロードされたファイルの拡張子を取得
        $extension = $select_file->getClientOriginalExtension();
        // ストレージに保存する際のファイル名を設定
        $save_file_name = 'item_upload_data_'.$nowDate->format('Y-m-d H-i-s').'.'.$extension;
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
        // ファイルタイプを判別（先方からの商品マスタなのか、smoothの商品マスタなのか）
        if($data_header[0] === '商品コード'){
            $file_type = ItemUploadEnum::PUSH_COLOR_ITEM_MASTER;
        }else{
            $file_type = ItemUploadEnum::SMOOTH_ITEM_MASTER;
        }
        // 必須ヘッダーを取得
        $required_header = ItemUploadEnum::get_required_header($upload_type, $file_type);
        // チェックするカラムの分だけループ処理
        foreach($required_header as $column){
            // カラムが存在するか確認
            $result = $this->checkValueExists($data_header, $column);
            // nullでなければエラーを返す
            if(!is_null($result)){
                throw new \RuntimeException($result);
            }
        }
        return $file_type;
    }

    // 配列の値が存在しているか確認
    public function checkValueExists($array, $value){
        // 存在したら「true」、存在しなかったら「false」
        $result = in_array($value, $array);
        // 存在しなかったら、エラーを返す
        return !$result ? 'カラムに「'.$value.'」がありません。' : null;
    }
}