<?php

namespace App\Services\Common;

abstract class BaseFilterService
{
    // セッションを削除
    public function deleteSession()
    {
        // 「filter_」で始まるキーを取得
        $keys = collect(session()->all())
                    ->keys()
                    ->filter(fn($key) => str_starts_with($key, 'filter_'));
        // セッションを削除
        session()->forget($keys->all());
    }

    // セッションに検索条件を格納
    public function setSearchCondition($request)
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // 「filter」の場合
        if($request->process_type === 'filter'){
            // 「filter_」から始まるキーパラメータをセッションに格納
            collect($request->all())
                    ->keys()
                    ->filter(fn($key) => str_starts_with($key, 'filter_'))
                    ->each(fn($key) => session([$key => $request->$key]));
        }
    }

    // サブクラスで定義
    abstract protected function baseQuery();
    abstract protected function likeKeys(): array;
    abstract protected function specialKeys(): array;
    abstract protected function applySort($query);

    protected function ignoreKeys(): array
    {
        return [];
    }

    public function getSearchResult()
    {
        // クエリを取得
        $query = $this->baseQuery();
        // 「filter_」で始まる要素をループ処理 
        collect(session()->all())
            ->filter(fn($value, $key) => str_starts_with($key, 'filter_') && $value != null)
            ->each(function ($value, $key) use (&$query) {
                // 無視するキーの場合はスキップ
                if (in_array($key, $this->ignoreKeys())) {
                    return;
                }
                // 特殊キーを取得
                $specialKeys = $this->specialKeys();
                // 特殊キーに存在する場合
                if (isset($specialKeys[$key])) {
                    // 該当する特殊キーのクロージャを実行
                    $specialKeys[$key]($query, $value);
                    return;
                }
                // 「filter_」を取り除いて格納
                $column = str_replace('filter_', '', $key);
                // LIKEキーに存在する場合
                if (in_array($key, $this->likeKeys())) {
                    $query->where($column, 'LIKE', '%' . $value . '%');
                } else {
                    $query->where($column, $value);
                }
            });
        // 並び替えを実施
        return $this->applySort($query);
    }
}