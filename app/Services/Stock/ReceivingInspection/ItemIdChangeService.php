<?php

namespace App\Services\Stock\ReceivingInspection;

// モデル
use App\Models\Item;

class ItemIdChangeService
{
    // 検品商品変更対象を取得
    public function getChangeTarget($request)
    {
        // 商品JANコードを条件に取得
        return Item::where('item_jan_code', $request->item_jan_code)->orderBy('item_id', 'asc')->get();
    }

    // 検品商品の変更
    public function changeItem($request)
    {
        // 変更先の商品を取得
        $item = Item::getSpecify($request->change_item_id)->first();
        // セッションの中身を配列にセット
        $progress = session('progress');
        // 配列の分だけループ処理
        foreach($progress as $key => $value){
            // item_idが一致した場合
            if($value['item_id'] == $request->item_id){
                // 要素のitem_idを変更
                $progress[$key]['item_id'] = $request->change_item_id;
                // 処理を抜ける
                break;
            }
        }
        // セッションへ戻す
        session(['progress' => $progress]);
        return $item;
    }
}