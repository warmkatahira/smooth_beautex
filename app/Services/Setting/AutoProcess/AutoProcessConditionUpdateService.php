<?php

namespace App\Services\Setting\AutoProcess;

// モデル
use App\Models\AutoProcessCondition;

class AutoProcessConditionUpdateService
{
    // 既存の自動処理条件を削除
    public function deleteAutoProcessCondition($auto_process_id)
    {
        AutoProcessCondition::where('auto_process_id', $auto_process_id)->delete();
    }

    // 自動処理条件を追加
    public function createAutoProcessCondition($request)
    {
        // 各情報を変数に格納
        $auto_process_id = $request->input('auto_process_id');
        $column_names = $request->input('column_name', []);
        $values = $request->input('value', []);
        $operators = $request->input('operator', []);
        // 情報の分だけループ処理
        foreach($column_names as $index => $column_name){
            // 追加
            AutoProcessCondition::create([
                'auto_process_id' => $auto_process_id,
                'column_name'     => $column_name,
                'value'           => $values[$index],
                'operator'        => $operators[$index],
            ]);
        }
    }
}