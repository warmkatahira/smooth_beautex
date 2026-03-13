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
            'filter_item_name',
            'filter_item_category_1',
            'filter_item_category_2',
        ];
    }

    // 特殊キー
    protected function specialKeys(): array
    {
        return [];
    }

    // 無視するキー
    protected function ignoreKeys(): array
    {
        return [
            'filter_lot',
            'filter_exp',
        ];
    }

    // 並び替え
    protected function applySort($query)
    {
        return $query->orderBy('items.sort_order', 'asc')
                    ->orderBy('items.item_code', 'asc');
    }
}