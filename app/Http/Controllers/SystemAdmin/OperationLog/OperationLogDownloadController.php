<?php

namespace App\Http\Controllers\SystemAdmin\OperationLog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\SystemAdmin\OperationLog\OperationLogSearchService;
use App\Services\SystemAdmin\OperationLog\OperationLogDownloadService;
// その他
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class OperationLogDownloadController extends Controller
{
    public function download()
    {
        // インスタンス化
        $OperationLogSearchService = new OperationLogSearchService;
        $OperationLogDownloadService = new OperationLogDownloadService;
        // ログ情報を取得
        $log_contents = $OperationLogSearchService->getLogContent();
        // ダウンロードするデータを取得
        $response = $OperationLogDownloadService->getDownloadData($log_contents);
        // ダウンロード処理
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=【'.SystemEnum::CUSTOMER_NAME.'】操作ログデータ_' . CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒') . '.csv');
        return $response;
    }
}
