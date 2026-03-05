<?php

namespace App\Services\Stock\StockHistory;

// モデル
use App\Models\StockHistory;
// 列挙
use App\Enums\SystemEnum;
// その他
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

class StockHistorySearchService
{
    // セッションを削除
    public function deleteSession()
    {
        session()->forget([
            'search_stock_history_date_from',
            'search_stock_history_date_to',
            'search_stock_history_category_id',
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
        // 変数が存在しない場合は検索が実行されていないので、初期条件をセット
        if(!isset($request->search_type)){
            session(['search_stock_history_date_from'   => CarbonImmutable::now()->toDateString()]);
            session(['search_stock_history_date_to'     => CarbonImmutable::now()->toDateString()]);
        }
        // 「search」なら検索が実行されているので、検索条件をセット
        if($request->search_type === 'search'){
            session(['search_stock_history_date_from' => $request->search_stock_history_date_from]);
            session(['search_stock_history_date_to' => $request->search_stock_history_date_to]);
            session(['search_stock_history_category_id' => $request->search_stock_history_category_id]);
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
        $query = StockHistory::join('stock_history_details', 'stock_history_details.stock_history_id', 'stock_histories.stock_history_id')
                    ->join('stocks', 'stocks.stock_id', 'stock_history_details.stock_id')
                    ->join('items', 'items.item_id', 'stocks.item_id')
                    ->join('bases', 'bases.base_id', 'stocks.base_id')
                    ->join('stock_history_categories', 'stock_history_categories.stock_history_category_id', 'stock_histories.stock_history_category_id')
                    ->select(
                        'stock_histories.stock_history_category_id',
                        'stock_histories.user_no',
                        'stock_histories.comment',
                        'stock_histories.updated_at',
                        'stock_history_details.quantity',
                        'stocks.base_id',
                        'items.item_code',
                        'items.item_jan_code',
                        'items.item_name',
                        'items.item_category',
                        'items.item_image_file_name',
                        'bases.base_name',
                        'bases.sort_order',
                        'stock_history_categories.stock_history_category_name',
                    );
        // 日付の条件がある場合
        if(!empty(session('search_stock_history_date_from')) && !empty(session('search_stock_history_date_to'))){
            $query->whereDate('stock_histories.updated_at', '>=', session('search_stock_history_date_from'))
                    ->whereDate('stock_histories.updated_at', '<=', session('search_stock_history_date_to'));
        }
        // 区分の条件がある場合
        if(session('search_stock_history_category_id') != null){
            // 条件を指定して取得
            $query = $query->where('stock_histories.stock_history_category_id', session('search_stock_history_category_id'));
        }
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
        // グループ化+並び替え
        return $query->groupBy(
            'stock_histories.stock_history_category_id',
            'stock_histories.user_no',
            'stock_histories.comment',
            'stock_histories.updated_at',
            'stock_history_details.quantity',
            'stocks.base_id',
            'items.item_code',
            'items.item_jan_code',
            'items.item_name',
            'items.item_category',
            'items.item_image_file_name',
            'bases.base_name',
            'bases.sort_order',
            'stock_history_categories.stock_history_category_name',
        )
        ->orderBy('stock_histories.updated_at', 'asc')
        ->orderBy('items.item_code', 'asc')
        ->orderBy('bases.sort_order', 'asc');
    }

    // ページネーションを実施
    public function setPagination($query)
    {
        // 指定された件数でページネーション
        return $query->paginate(SystemEnum::PAGINATE_DEFAULT);
    }
}