<?php

namespace App\Services\Common;

class ImportErrorCreateService
{
    // インポートエラー情報のファイルを作成
    public function createImportError($file_name_prefix, $error, $nowDate, $error_content, $key_name)
    {
        // チャンクサイズを設定
        $chunk_size = 500;
        // チャンクサイズ毎に分割
        $chunks = array_chunk($error, $chunk_size);
        // ファイル名を設定
        $csvFileName = $file_name_prefix.$nowDate->format('Y-m-d H-i-s').'.csv';
        // 保存場所を設定
        $csvFilePath = storage_path('app/public/export/order_import_error/'.$csvFileName);
        // エラー内容により可変
        if($error_content == '新規取込なし'){
            // ヘッダ行を書き込む
            $header = ['注文番号', 'エラー内容'];
            $csvContent = "\xEF\xBB\xBF" . implode(',', $header) . "\n";
            // チャンク毎のループ処理
            foreach($chunks as $chunk){
                // レコード毎のループ処理
                foreach($chunk as $item){
                    $row = [$item[$key_name], '取込済み'];
                    $csvContent .= implode(',', $row) . "\n";
                }
            }
        }
        if($error_content == 'データ不正'){
            // ヘッダ行を書き込む
            $header = ['エラー行数', 'エラー内容'];
            $csvContent = "\xEF\xBB\xBF" . implode(',', $header) . "\n";
            // チャンク毎のループ処理
            foreach($chunks as $chunk){
                // レコード毎のループ処理
                foreach($chunk as $item){
                    $row = [$item['エラー行数'], $item['エラー内容']];
                    $csvContent .= implode(',', $row) . "\n";
                }
            }
        }
        if($error_content == 'ヘッダー不正'){
            // ヘッダ行を書き込む
            $header = ['エラー内容'];
            $csvContent = "\xEF\xBB\xBF" . implode(',', $header) . "\n";
            // チャンク毎のループ処理
            foreach($chunks as $chunk){
                // レコード毎のループ処理
                foreach($chunk as $item){
                    $row = [$item];
                    $csvContent .= implode(',', $row) . "\n";
                }
            }
        }
        // ファイルに出力
        file_put_contents($csvFilePath, $csvContent);
        return $csvFileName;
    }
}