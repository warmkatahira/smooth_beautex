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
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // ファイルを出力するディレクトリを作成
        $directory = $ShippingActualDownloadService->makeDirectory($nowDate);
        // 検索結果を取得
        $result = $OrderSearchService->getSearchResult();
        // ファイルを作成
        $ShippingActualDownloadService->createFile($nowDate, $result, $directory['directory_path']);
        // Zipファイルを作成
        $zip_file_path = $ShippingActualDownloadService->createZip($directory['directory_name'], $directory['directory_path']);
        // 作成したZIPファイルをダウンロード(ダウンロード後に削除している)
        return response()->download($zip_file_path)->deleteFileAfterSend(true);
    }
}
