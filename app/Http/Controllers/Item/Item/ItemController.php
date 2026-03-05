<?php

namespace App\Http\Controllers\Item\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Item;
// サービス
use App\Services\Item\Item\ItemSearchService;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '商品']);
        // インスタンス化
        $ItemSearchService = new ItemSearchService;
        // セッションを削除
        $ItemSearchService->deleteSession();
        // セッションに検索条件を格納
        $ItemSearchService->setSearchCondition($request);
        // 検索結果を取得
        $result = $ItemSearchService->getSearchResult();
        // ページネーションを実施
        $items = $ItemSearchService->setPagination($result);
        return view('item.item.index')->with([
            'items' => $items,
        ]);
    }
}