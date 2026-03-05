<?php

namespace App\Http\Controllers\Stock\StockHistory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\StockHistoryCategory;
use App\Models\Item;
// サービス
use App\Services\Stock\StockHistory\StockHistorySearchService;

class StockHistoryController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '在庫履歴']);
        // インスタンス化
        $StockHistorySearchService = new StockHistorySearchService;
        // セッションを削除
        $StockHistorySearchService->deleteSession();
        // セッションに検索条件を格納
        $StockHistorySearchService->setSearchCondition($request);
        // 検索結果を取得
        $result = $StockHistorySearchService->getSearchResult();
        // ページネーションを実施
        $stock_histories = $StockHistorySearchService->setPagination($result);
        // 在庫履歴区分を取得
        $stock_history_categories = StockHistoryCategory::getAll()->get();       
        return view('stock.stock_history.index')->with([
            'stock_histories' => $stock_histories,
            'stock_history_categories' => $stock_history_categories,
        ]);
    }
}