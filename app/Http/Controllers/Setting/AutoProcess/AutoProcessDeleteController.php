<?php

namespace App\Http\Controllers\Setting\AutoProcess;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Setting\AutoProcess\AutoProcessDeleteService;
// リクエスト
use App\Http\Requests\Setting\AutoProcess\AutoProcessDeleteRequest;
// その他
use Illuminate\Support\Facades\DB;

class AutoProcessDeleteController extends Controller
{
    public function delete(AutoProcessDeleteRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $AutoProcessDeleteService = new AutoProcessDeleteService;
                // 自動処理を削除
                $AutoProcessDeleteService->deleteAutoProcess($request);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => '自動処理の削除に失敗しました。',
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '自動処理を削除しました。',
        ]);
    }
}