<?php

namespace App\Services\SystemAdmin\OperationLog;

// その他
use Symfony\Component\HttpFoundation\StreamedResponse;
// 列挙
use App\Enums\OperationLogEnum;

class OperationLogDownloadService
{
    // ダウンロードするデータを取得
    public function getDownloadData($log_contents)
    {
        $response = new StreamedResponse(function () use ($log_contents){
            // ハンドルを取得
            $handle = fopen('php://output', 'wb');
            // BOMを書き込む
            fwrite($handle, "\xEF\xBB\xBF");
            // システムに定義してあるヘッダーを取得し、書き込む
            $header = OperationLogEnum::downloadHeader();
            fputcsv($handle, $header);
            // ログの分だけループ処理
            foreach($log_contents as $log_content){
                // 配列に格納
                $row = [
                    $log_content['operation_date'],
                    $log_content['operation_time'],
                    $log_content['user_name'],
                    $log_content['ip_address'],
                    $log_content['method'],
                    $log_content['path'],
                    $log_content['param'],
                ];
                // ログ情報を書き込む
                fputcsv($handle, $row);
            };
            // ファイルを閉じる
            fclose($handle);
        });
        return $response;
    }
}