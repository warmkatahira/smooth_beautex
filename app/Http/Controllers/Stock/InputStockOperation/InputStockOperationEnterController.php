<?php

namespace App\Http\Controllers\Stock\InputStockOperation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Stock\InputStockOperation\InputStockOperationEnterService;
use App\Services\Common\StockUpdateService;
use App\Services\Common\StockHistoryCreateService;
// 列挙
use App\Enums\StockHistoryCategoryEnum;
// その他
use Illuminate\Support\Facades\DB;

class InputStockOperationEnterController extends Controller
{
    public function enter(Request $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $InputStockOperationEnterService = new InputStockOperationEnterService;
                $StockUpdateService = new StockUpdateService;
                $StockHistoryCreateService = new StockHistoryCreateService;
                // 在庫操作するデータを取得
                $stock_update_arr = $InputStockOperationEnterService->getOperationData($request->quantity);
                // stocksにレコードがない在庫を追加
                $InputStockOperationEnterService->createNoStockRecord($stock_update_arr);
                // 在庫操作するデータを整理
                $stock_update_arr = $InputStockOperationEnterService->updateStockUpdateArr($stock_update_arr);
                // 在庫操作できる内容か確認
                $InputStockOperationEnterService->check($stock_update_arr);
                // 在庫数を更新
                $StockUpdateService->updateStock($stock_update_arr);
                // 在庫履歴に追加
                $StockHistoryCreateService->createStcokHistory($request->proc_type, $request->comment, $stock_update_arr);
            });
        }catch (\Exception $e){
            return redirect()->back()->withInput()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '在庫操作が完了しました。',
        ]);
    }
}