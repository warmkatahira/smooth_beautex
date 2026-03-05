<?php

namespace App\Http\Controllers\Stock\ReceivingInspection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Stock\ReceivingInspection\ReceivingInspectionEnterService;
use App\Services\Common\StockUpdateService;
use App\Services\Common\StockHistoryCreateService;
// 列挙
use App\Enums\StockHistoryCategoryEnum;
// その他
use Illuminate\Support\Facades\DB;

class ReceivingInspectionEnterController extends Controller
{
    public function enter(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // インスタンス化
                $ReceivingInspectionEnterService = new ReceivingInspectionEnterService;
                $StockHistoryCreateService = new StockHistoryCreateService;
                $StockUpdateService = new StockUpdateService;
                // stocksにレコードがない在庫を追加
                $ReceivingInspectionEnterService->createNoStockRecord($request);
                // 入庫対象の情報を配列に格納
                $stock_update_arr = $ReceivingInspectionEnterService->setArray($request);
                // 在庫数を更新
                $StockUpdateService->updateStock($stock_update_arr);
                // 在庫履歴に追加
                $StockHistoryCreateService->createStcokHistory(StockHistoryCategoryEnum::NYUKO, $request->comment, $stock_update_arr);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '入庫検品確定が完了しました。',
        ]);
    }
}