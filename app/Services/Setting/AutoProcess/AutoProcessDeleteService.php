<?php

namespace App\Services\Setting\AutoProcess;

// モデル
use App\Models\AutoProcess;

class AutoProcessDeleteService
{
    // 自動処理を削除
    public function deleteAutoProcess($request)
    {
        // 自動処理を削除
        AutoProcess::getSpecify($request->auto_process_id)->delete();
    }
}