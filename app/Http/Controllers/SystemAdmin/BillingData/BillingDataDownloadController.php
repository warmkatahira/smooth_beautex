<?php

namespace App\Http\Controllers\SystemAdmin\BillingData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\SystemAdmin\BillingData\BillingDataDownloadService;
// その他
use ZipArchive;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

class BillingDataDownloadController extends Controller
{
    public function download(Request $request)
    {

        try{
            $zip_file_path = DB::transaction(function () use ($request){
                // 現在の日時を取得
                $nowDate = CarbonImmutable::now();
                // インスタンス化
                $BillingDataDownloadService = new BillingDataDownloadService;
                // ダウンロード内容の確認
                $BillingDataDownloadService->checkDownload($request);
                // ファイルを出力するディレクトリを作成
                $directory = $BillingDataDownloadService->makeDirectory($nowDate);
                // ファイルを作成
                $BillingDataDownloadService->createFile($nowDate, $request, $directory['directory_path']);
                // Zipファイルを作成
                return $BillingDataDownloadService->createZip($directory['directory_name'], $directory['directory_path']);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        // 作成したZIPファイルをダウンロード(ダウンロード後に削除している)
        return response()->download($zip_file_path)->deleteFileAfterSend(true);
    }
}