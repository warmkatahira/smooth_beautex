<?php

namespace App\Services\Setting\AutoProcess;

// モデル
use App\Models\AutoProcess;
// 列挙
use App\Enums\SystemEnum;
// その他
use Illuminate\Support\Facades\DB;

class AutoProcessSearchService
{
    // セッションを削除
    public function deleteSession()
    {
        session()->forget([
            'search_auto_process_name',
            'search_action_type',
            'search_is_active',
        ]);
        return;
    }

    // セッションに検索条件を格納
    public function setSearchCondition($request)
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // 変数が存在しない場合は検索が実行されていないので、初期条件をセット
        if(!isset($request->search_type)){
        }
        // 「search」なら検索が実行されているので、検索条件をセット
        if($request->search_type === 'search'){
            session(['search_auto_process_name' => $request->search_auto_process_name]);
            session(['search_action_type' => $request->search_action_type]);
            session(['search_is_active' => $request->search_is_active]);
        }
        return;
    }

    // 検索結果を取得
    public function getSearchResult()
    {
        // クエリをセット
        $query = AutoProcess::query()->with('auto_process_conditions');
        // 自動処理名の条件がある場合
        if(session('search_auto_process_name') != null){
            // 条件を指定して取得
            $query = $query->where('auto_process_name', 'LIKE', '%'.session('search_auto_process_name').'%');
        }
        // アクション区分の条件がある場合
        if(session('search_action_type') != null){
            // 条件を指定して取得
            $query = $query->where('action_type', session('search_action_type'));
        }
        // 有効/無効の条件がある場合
        if(session('search_is_active') != null){
            // 条件を指定して取得
            $query = $query->where('is_active', session('search_is_active'));
        }
        // 並び替えを実施
        return $query->orderBy('sort_order', 'asc')->orderBy('auto_process_id', 'asc');
    }

    // ページネーションを実施
    public function setPagination($query)
    {
        // 指定された件数でページネーション
        return $query->paginate(SystemEnum::PAGINATE_DEFAULT);
    }
}