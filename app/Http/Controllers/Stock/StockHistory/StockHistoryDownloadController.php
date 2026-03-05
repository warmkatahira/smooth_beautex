<?php

namespace App\Http\Controllers\Stock\StockHistory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Stock\StockHistory\StockHistorySearchService;
use App\Services\Stock\StockHistory\StockHistoryDownloadService;
// その他
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class StockHistoryDownloadController extends Controller
{
    public function download()
    {
        // インスタンス化
        $StockHistorySearchService = new StockHistorySearchService;
        $StockHistoryDownloadService = new StockHistoryDownloadService;
        // 検索結果を取得
        $result = $StockHistorySearchService->getSearchResult();
        // ダウンロードするデータを取得
        $response = $StockHistoryDownloadService->getDownloadData($result);
        // ダウンロード処理
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=【'.SystemEnum::CUSTOMER_NAME.'】在庫履歴データ_' . CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒') . '.csv');
        return $response;
    }
}
