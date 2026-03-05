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
use App\Services\Setting\AutoProcess\AutoProcessConditionUpdateService;
// リクエスト
use App\Http\Requests\Setting\AutoProcess\AutoProcessConditionUpdateRequest;
// その他
use Illuminate\Support\Facades\DB;

class AutoProcessConditionUpdateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '自動処理条件設定']);
        // 自動処理を取得
        $auto_process = AutoProcess::getSpecify($request->auto_process_id)->with('auto_process_conditions')->first();
        // カラム名を取得
        $column_names = AutoProcessEnum::COLUMN_NAME_LIST;
        // 比較演算子を取得
        $operators = AutoProcessEnum::OPERATOR_LIST;
        // 運送会社を取得
        $delivery_companies = DeliveryCompany::getAll()->with('shipping_methods')->get();
        return view('setting.auto_process.condition_update')->with([
            'auto_process' => $auto_process,
            'column_names' => $column_names,
            'operators' => $operators,
            'delivery_companies' => $delivery_companies,
        ]);
    }

    public function ajax_validation(AutoProcessConditionUpdateRequest $request)
    {
        return response()->json([]);
    }

    public function update(Request $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $AutoProcessConditionUpdateService = new AutoProcessConditionUpdateService;
                // 既存の自動処理条件を削除
                $AutoProcessConditionUpdateService->deleteAutoProcessCondition($request->auto_process_id);
                // 自動処理条件を追加
                $AutoProcessConditionUpdateService->createAutoProcessCondition($request);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => '自動処理条件の設定に失敗しました。',
            ]);
        }
        return redirect()->route('auto_process.index')->with([
            'alert_type' => 'success',
            'alert_message' => '自動処理条件を設定しました。',
        ]);
    }
}