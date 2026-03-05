<?php

namespace App\Services\Item\Item;

// モデル
use App\Models\Item;
// 列挙
use App\Enums\SystemEnum;
// その他
use Illuminate\Support\Facades\DB;

class ItemSearchService
{
    // セッションを削除
    public function deleteSession()
    {
        session()->forget([
            'search_item_code',
            'search_item_jan_code',
            'search_item_name',
            'search_item_category',
        ]);
        return;
    }

    // セッションに検索条件を格納
    public function setSearchCondition($request)
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // 変数が存在しない場合は検索が実行されていないので、初期条件をセット
        if(!isset($request->search_type)){
        }
        // 「search」なら検索が実行されているので、検索条件をセット
        if($request->search_type === 'search'){
            session(['search_item_code' => $request->search_item_code]);
            session(['search_item_jan_code' => $request->search_item_jan_code]);
            session(['search_item_name' => $request->search_item_name]);
            session(['search_item_category' => $request->search_item_category]);
        }
        return;
    }

    // 検索結果を取得
    public function getSearchResult()
    {
        // クエリをセット
        $query = Item::query();
        // 商品コードの条件がある場合
        if(session('search_item_code') != null){
            // 条件を指定して取得
            $query = $query->where('item_code', 'LIKE', '%'.session('search_item_code').'%');
        }
        // 商品JANコードの条件がある場合
        if(session('search_item_jan_code') != null){
            // 条件を指定して取得
            $query = $query->where('item_jan_code', 'LIKE', '%'.session('search_item_jan_code').'%');
        }
        // 商品名の条件がある場合
        if(session('search_item_name') != null){
            // 条件を指定して取得
            $query = $query->where('item_name', 'LIKE', '%'.session('search_item_name').'%');
        }
        // 商品カテゴリの条件がある場合
        if(session('search_item_category') != null){
            // 条件を指定して取得
            $query = $query->where('item_category', 'LIKE', '%'.session('search_item_category').'%');
        }
        // 並び替えを実施
        return $query->orderBy('items.sort_order', 'asc')->orderBy('items.item_code', 'asc');
    }

    // ページネーションを実施
    public function setPagination($query)
    {
        // 指定された件数でページネーション
        return $query->paginate(SystemEnum::PAGINATE_DEFAULT);
    }
}