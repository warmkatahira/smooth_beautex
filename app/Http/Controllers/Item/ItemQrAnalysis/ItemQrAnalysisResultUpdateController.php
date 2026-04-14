<?php

namespace App\Http\Controllers\Item\ItemQrAnalysis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Item\ItemQrAnalysis\ItemQrAnalysisResultUpdateService;
// リクエスト
use App\Http\Requests\Item\ItemQrAnalysis\ItemQrAnalysisResultUpdateRequest;
// その他
use Illuminate\Support\Facades\DB;

class ItemQrAnalysisResultUpdateController extends Controller
{
    public function update(Request $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $ItemQrAnalysisResultUpdateService = new ItemQrAnalysisResultUpdateService;
                // 商品QR解析履歴を取得
                $item_qr_analysis_history = $ItemQrAnalysisResultUpdateService->getItemQrAnalysisHistory($request);
                // 商品を取得
                $item = $ItemQrAnalysisResultUpdateService->getItem($item_qr_analysis_history);
                // 商品QR解析結果を反映
                $ItemQrAnalysisResultUpdateService->updateItemQrAnalysisResult($item, $item_qr_analysis_history);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->route('item_qr_analysis.index')->with([
            'alert_type' => 'success',
            'alert_message' => '商品QR解析結果を反映しました。',
        ]);
    }
}