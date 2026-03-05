<?php

namespace App\Http\Controllers\Setting\AutoProcess;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\AutoProcess;
use App\Models\DeliveryCompany;
// 列挙
use App\Enums\AutoProcessEnum;
// サービス
use App\Services\Setting\AutoProcess\AutoProcessUpdateService;
// リクエスト
use App\Http\Requests\Setting\AutoProcess\AutoProcessUpdateRequest;
// その他
use Illuminate\Support\Facades\DB;

class AutoProcessUpdateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '自動処理更新']);
        // 自動処理を取得
        $auto_process = AutoProcess::getSpecify($request->auto_process_id)->with('auto_process_order_item')->first();
        // アクション区分を取得
        $action_types = AutoProcessEnum::ACTION_TYPE_LIST;
        // カラム名を取得
        $column_names = AutoProcessEnum::COLUMN_NAME_LIST;
        // 条件一致区分を取得
        $condition_match_types = AutoProcessEnum::CONDITION_MATCH_TYPE_LIST;
        // 運送会社を取得
        $delivery_companies = DeliveryCompany::getAll()->with('shipping_methods')->get();
        return view('setting.auto_process.update')->with([
            'auto_process' => $auto_process,
            'action_types' => $action_types,
            'column_names' => $column_names,
            'condition_match_types' => $condition_match_types,
            'delivery_companies' => $delivery_companies,
        ]);
    }

    public function update(AutoProcessUpdateRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $AutoProcessUpdateService = new AutoProcessUpdateService;
                // 自動処理を更新
                $AutoProcessUpdateService->updateAutoProcess($request);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => '自動処理の更新に失敗しました。',
            ]);
        }
        return redirect()->route('auto_process.index')->with([
            'alert_type' => 'success',
            'alert_message' => '自動処理を更新しました。',
        ]);
    }
}