<?php

namespace App\Traits;

// 列挙
use App\Enums\SystemEnum;
// その他
use Illuminate\Support\Facades\Auth;

trait PaginatesResultsTrait
{
    protected function setPagination($query)
    {
        return $query->paginate(SystemEnum::PAGINATE_DEFAULT);
    }
}