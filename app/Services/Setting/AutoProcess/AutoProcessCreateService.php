<?php

namespace App\Services\Setting\AutoProcess;

// モデル
use App\Models\AutoProcess;
use App\Models\AutoProcessOrderItem;
// 列挙
use App\Enums\AutoProcessEnum;

class AutoProcessCreateService
{
    // 自動処理を追加
    public function createAutoProcess($request)
    {
        // 変数を初期化
        $action_column_name = null;
        // アクション区分が「受注商品を追加」以外の場合
        if($request->action_type != AutoProcessEnum::ORDER_ITEM_CREATE){
            // アクションカラム名を取得
            $action_column_name = AutoProcessEnum::getActionTypeColumnName($request->action_type);
        }
        // 自動処理を追加
        $auto_process = AutoProcess::create([
            'auto_process_name'     => $request->auto_process_name,
            'action_type'           => $request->action_type,
            'action_column_name'    => $action_column_name,
            'action_value'          => $request->action_value ?? null,
            'condition_match_type'  => $request->condition_match_type,
            'is_active'             => $request->is_active,
            'sort_order'            => $request->sort_order,
        ]);
        // アクション区分が「受注商品を追加」の場合
        if($request->action_type === AutoProcessEnum::ORDER_ITEM_CREATE){
            // 自動処理受注商品を追加
            AutoProcessOrderItem::create([
                'auto_process_id'   => $auto_process->auto_process_id,
                'order_item_code'   => $request->order_item_code,
                'order_item_name'   => $request->order_item_name,
                'order_quantity'    => $request->order_quantity,
            ]);
        }
    }
}