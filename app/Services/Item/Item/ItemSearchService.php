<?php

namespace App\Services\Item\Item;

// モデル
use App\Models\Item;
// サービス
use App\Services\Common\BaseFilterService;
// 列挙
use App\Enums\SystemEnum;
// その他
use Illuminate\Support\Facades\DB;

class ItemSearchService extends BaseFilterService
{
    // ベースクエリ
    protected function baseQuery()
    {
        // クエリをセット
        return Item::query();
    }

    // LIKEキー
    protected function likeKeys(): array
    {
        return [
            'filter_item_code',
            'filter_item_jan_code',
            'filter_color_id',
            'filter_item_name',
            'filter_item_category_1',
            'filter_item_category_2',
            'filter_brand',
            'filter_wearing_period',
            'filter_quantity_per_box',
            'filter_manufacturer',
            'filter_supplier',
            'filter_ems_item_name',
        ];
    }

    // 特殊キー
    protected function specialKeys(): array
    {
        return [
            // カラーROW
            'filter_color_row' => function ($query, $value) {
                $query->where('color_row', '=', $value);
            },
        ];
    }

    // 無視するキー
    protected function ignoreKeys(): array
    {
        return [
            'filter_lot',
            'filter_exp',
            'filter_total_stock_min',
            'filter_total_stock_max',
        ];
    }

    // 並び替え
    protected function applySort($query)
    {
        return $query->orderBy('items.sort_order', 'asc')
                    ->orderBy('items.item_code', 'asc');
    }
}