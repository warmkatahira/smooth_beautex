<?php

namespace App\Http\Controllers\Order\HikiatezanDownload;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Order\HikiatezanDownload\HikiatezanDownloadService;
// その他
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
// 列挙
use App\Enums\SystemEnum;

class HikiatezanDownloadController extends Controller
{
    public function download(Request $request)
    {
        try{
            $response = DB::transaction(function () use ($request){
                // インスタンス化
                $HikiatezanDownloadService = new HikiatezanDownloadService;
                // ダウンロード対象を取得
                $hikiatezan = $HikiatezanDownloadService->getDownloadTarget($request->chk);
                // ダウンロードするデータを取得
                return $HikiatezanDownloadService->getDownloadData($hikiatezan);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        // ダウンロード処理
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=【'.SystemEnum::CUSTOMER_NAME_JP.'様】引当残データ_' . CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒') . '.csv');
        return $response;
    }
}