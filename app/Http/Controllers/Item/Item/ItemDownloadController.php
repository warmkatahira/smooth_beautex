<?php

namespace App\Http\Controllers\Item\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Item\Item\ItemSearchService;
use App\Services\Item\Item\ItemDownloadService;
// その他
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class ItemDownloadController extends Controller
{
    public function download()
    {
        // インスタンス化
        $ItemSearchService = new ItemSearchService;
        $ItemDownloadService = new ItemDownloadService;
        // 検索結果を取得
        $result = $ItemSearchService->getSearchResult();
        // ダウンロードするデータを取得
        $response = $ItemDownloadService->getDownloadData($result);
        // ダウンロード処理
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=【'.SystemEnum::CUSTOMER_NAME.'】商品データ_' . CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒') . '.csv');
        return $response;
    }
}
