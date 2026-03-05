<?php

namespace App\Http\Controllers\Shipping\ShippingHistory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Order\OrderMgt\OrderSearchService;
use App\Services\Shipping\ShippingHistory\ShippingActualDownloadService;
// その他
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class ShippingActualDownloadController extends Controller
{
    public function download()
    {
         // インスタンス化
        $OrderSearchService = new OrderSearchService;
        $ShippingActualDownloadService = new ShippingActualDownloadService;
        // 検索結果を取得
        $result = $OrderSearchService->getSearchResult();
        // ダウンロードするデータを取得
        $response = $ShippingActualDownloadService->getDownloadData($result);
        // ダウンロード処理
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=【'.SystemEnum::CUSTOMER_NAME.'】出荷実績データ_' . CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒') . '.csv');
        return $response;
    }
}
