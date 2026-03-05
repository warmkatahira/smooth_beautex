<?php

namespace App\Http\Controllers\Stock\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Item\Item\ItemSearchService;
use App\Services\Stock\Stock\StockSearchService;
use App\Services\Stock\Stock\StockDownloadService;
// その他
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Route;
// 列挙
use App\Enums\SystemEnum;

class StockDownloadController extends Controller
{
    public function download(Request $request)
    {
        // インスタンス化
        $ItemSearchService = new ItemSearchService;
        $StockSearchService = new StockSearchService;
        $StockDownloadService = new StockDownloadService;
        // 検索結果を取得
        $result = $ItemSearchService->getSearchResult();
        $result = $StockSearchService->getSearchResult($result, $request->route_name);
        // ダウンロードするデータを取得
        $response = $StockDownloadService->getDownloadData($result['stocks'], $result['bases'], $request->route_name);
        // ダウンロード処理
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=【'.SystemEnum::CUSTOMER_NAME.'】在庫データ_' . CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒') . '.csv');
        return $response;
    }
}
