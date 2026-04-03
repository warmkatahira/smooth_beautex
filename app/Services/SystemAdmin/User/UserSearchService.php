<?php

namespace App\Services\SystemAdmin\User;

// モデル
use App\Models\User;
// サービス
use App\Services\Common\BaseFilterService;
// 列挙
use App\Enums\SystemEnum;
// その他
use Illuminate\Support\Facades\DB;

class UserSearchService extends BaseFilterService
{
    // ベースクエリ
    protected function baseQuery()
    {
        // クエリをセット
        return User::with(['role', 'company']);
    }

    // LIKEキー
    protected function likeKeys(): array
    {
        return [
            'filter_user_id',
            'filter_email',
        ];
    }

    // 特殊キー
    protected function specialKeys(): array
    {
        return [
            // 氏名
            'filter_full_name' => function ($query, $value) {
                $query->where(function ($q) use ($value) {
                    $q->where('last_name', 'like', '%' . $value . '%')
                    ->orWhere('first_name', 'like', '%' . $value . '%')
                    ->orWhereRaw("CONCAT(last_name, ' ', first_name) like ?", ['%' . $value . '%']);
                });
            },
            // 権限
            'filter_role_id' => function ($query, $value) {
                $query->whereHas('role', function ($q) use ($value) {
                    $q->where('role_id', $value);
                });
            },
            // 会社
            'filter_company_id' => function ($query, $value) {
                $query->whereHas('company', function ($q) use ($value) {
                    $q->where('company_id', $value);
                });
            },
        ];
    }

    // 無視するキー
    protected function ignoreKeys(): array
    {
        return [];
    }

    // 並び替え
    protected function applySort($query)
    {
        return $query->orderBy('users.user_no', 'asc');
    }
}