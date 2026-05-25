<?php

namespace App\Services\Stock\StockHistory;

// モデル
use App\Models\StockHistory;
// サービス
use App\Services\Common\BaseFilterService;
// 列挙
use App\Enums\SystemEnum;
// その他
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

class StockHistorySearchService extends BaseFilterService
{
    // セッションに検索条件を格納
    public function setSearchCondition($request)
    {
        // 変数が存在しない場合は検索が実行されていないので、初期条件をセット
        if(!isset($request->process_type)){
            // 当日の日付をセッションに格納
            session(['filter_history_date_from' => CarbonImmutable::now()->toDateString()]);
            session(['filter_history_date_to' => CarbonImmutable::now()->toDateString()]);
        }
        // 「filter」なら検索が実行されているので、検索条件をセット
        if($request->process_type === 'filter'){
            parent::setSearchCondition($request);
        }
    }

    // ベースクエリ
    protected function baseQuery()
    {
        // クエリをセット
        return StockHistory::join('stock_history_details', 'stock_history_details.stock_history_id', 'stock_histories.stock_history_id')
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
                                    'items.item_category_1',
                                    'items.item_image_file_name',
                                    'bases.base_name',
                                    'bases.sort_order',
                                    'stock_history_categories.stock_history_category_name',
                                    'stocks.lot',
                                    'stocks.exp',
                                );
    }

    // LIKEキー
    protected function likeKeys(): array
    {
        return [
            'filter_item_code',
            'filter_item_jan_code',
            'filter_item_name',
            'filter_item_category_1',
            'filter_item_category_2',
            'filter_comment',
        ];
    }

    // 特殊キー
    protected function specialKeys(): array
    {
        return [
            // 履歴日
            'filter_history_date_from' => function ($query, $value) {
                $query->whereDate('stock_histories.updated_at', '>=', session('filter_history_date_from'))
                    ->whereDate('stock_histories.updated_at', '<=', session('filter_history_date_to'));
            },
            // 履歴時間
            'filter_history_time' => function ($query, $value) {
                $query->whereRaw("TIME(stock_histories.updated_at) LIKE ?", [session('filter_history_time') . '%']);
            },
            // 区分
            'filter_stock_history_category_id' => function ($query, $value) {
                $query->where('stock_histories.stock_history_category_id', $value);
            },
            // 数量
            'filter_quantity' => function ($query, $value) {
                $query->where('stock_history_details.quantity', (int)$value);
            },
        ];
    }

    // 無視するキー
    protected function ignoreKeys(): array
    {
        return [
            'filter_history_date_to',
        ];
    }

    // 並び替え
    protected function applySort($query)
    {
        return $query->orderBy('stock_histories.updated_at', 'asc')
                    ->orderBy('items.item_code', 'asc')
                    ->orderBy('bases.sort_order', 'asc');
    }
}