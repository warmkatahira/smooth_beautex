<?php

namespace App\Http\Controllers\Stock\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Stock;
// サービス
use App\Services\Stock\Stock\StockUpdateService;
// リクエスト
use App\Http\Requests\Stock\Stock\StockUpdateRequest;
// その他
use Illuminate\Support\Facades\DB;

class StockUpdateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '在庫更新']);
        // 在庫を取得
        $stock = Stock::getSpecify($request->stock_id)->with('item')->first();
        return view('stock.stock.update')->with([
            'stock' => $stock,
        ]);
    }

    public function update(StockUpdateRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $StockUpdateService = new StockUpdateService;
                // 在庫を更新
                $stock = $StockUpdateService->updateStock($request);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect(session('back_url_1'))->with([
            'alert_type' => 'success',
            'alert_message' => '在庫を更新しました。',
        ]);
    }
}