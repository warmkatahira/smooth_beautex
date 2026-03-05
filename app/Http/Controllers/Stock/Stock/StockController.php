<?php

namespace App\Http\Controllers\Stock\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Item;
// サービス
use App\Services\Item\Item\ItemSearchService;
use App\Services\Stock\Stock\StockSearchService;
// その他
use Illuminate\Support\Facades\Route;

class StockController extends Controller
{
    public function index_by_item(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '在庫(商品単位)']);
        // インスタンス化
        $ItemSearchService = new ItemSearchService;
        $StockSearchService = new StockSearchService;
        // セッションを削除
        $ItemSearchService->deleteSession();
        $StockSearchService->deleteSession();
        // セッションに検索条件を格納
        $ItemSearchService->setSearchCondition($request);
        $StockSearchService->setSearchCondition($request);
        // 検索結果を取得
        $result = $ItemSearchService->getSearchResult();
        $result = $StockSearchService->getSearchResult($result, Route::currentRouteName());
        // ページネーションを実施
        $stocks = $ItemSearchService->setPagination($result['stocks']);
        return view('stock.stock.index_by_item')->with([
            'stocks' => $stocks,
            'bases' => $result['bases'],
        ]);
    }

    public function index_by_stock(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '在庫(在庫単位)']);
        // インスタンス化
        $ItemSearchService = new ItemSearchService;
        $StockSearchService = new StockSearchService;
        // セッションを削除
        $ItemSearchService->deleteSession();
        $StockSearchService->deleteSession();
        // セッションに検索条件を格納
        $ItemSearchService->setSearchCondition($request);
        $StockSearchService->setSearchCondition($request);
        // 検索結果を取得
        $result = $ItemSearchService->getSearchResult();
        $result = $StockSearchService->getSearchResult($result, Route::currentRouteName());
        // ページネーションを実施
        $stocks = $ItemSearchService->setPagination($result['stocks']);
        return view('stock.stock.index_by_stock')->with([
            'stocks' => $stocks,
            'bases' => $result['bases'],
        ]);
    }
}