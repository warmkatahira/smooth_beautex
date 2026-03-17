<?php

namespace App\Services\Setting\AutoProcess;

// モデル
use App\Models\AutoProcess;
// サービス
use App\Services\Common\BaseFilterService;

class AutoProcessSearchService extends BaseFilterService
{
    // ベースクエリ
    protected function baseQuery()
    {
        // クエリをセット
        return AutoProcess::query()->with('auto_process_conditions');
    }

    // LIKEキー
    protected function likeKeys(): array
    {
        return [
            'filter_auto_process_name',
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
        return [];
    }

    // 並び替え
    protected function applySort($query)
    {
        return $query->orderBy('sort_order', 'asc')
                    ->orderBy('auto_process_id', 'asc');
    }
}