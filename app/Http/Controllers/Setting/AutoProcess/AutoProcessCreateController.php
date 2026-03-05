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
use App\Services\Setting\AutoProcess\AutoProcessCreateService;
// リクエスト
use App\Http\Requests\Setting\AutoProcess\AutoProcessCreateRequest;
// その他
use Illuminate\Support\Facades\DB;

class AutoProcessCreateController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '自動処理追加']);
        // アクション区分を取得
        $action_types = AutoProcessEnum::ACTION_TYPE_LIST;
        // カラム名を取得
        $column_names = AutoProcessEnum::COLUMN_NAME_LIST;
        // 条件一致区分を取得
        $condition_match_types = AutoProcessEnum::CONDITION_MATCH_TYPE_LIST;
        // 運送会社を取得
        $delivery_companies = DeliveryCompany::getAll()->with('shipping_methods')->get();
        return view('setting.auto_process.create')->with([
            'action_types' => $action_types,
            'column_names' => $column_names,
            'condition_match_types' => $condition_match_types,
            'delivery_companies' => $delivery_companies,
        ]);
    }

    public function create(AutoProcessCreateRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $AutoProcessCreateService = new AutoProcessCreateService;
                // 自動処理を追加
                $AutoProcessCreateService->createAutoProcess($request);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => '自動処理の追加に失敗しました。',
            ]);
        }
        return redirect()->route('auto_process.index')->with([
            'alert_type' => 'success',
            'alert_message' => '自動処理を追加しました。',
        ]);
    }
}