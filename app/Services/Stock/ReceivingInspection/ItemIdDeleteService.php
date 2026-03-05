<?php

namespace App\Services\Stock\ReceivingInspection;

class ItemIdDeleteService
{
    // 検品商品を削除
    public function delete($request)
    {
        // セッションの中身を配列にセット
        $progress = session('progress');
        // 配列の分だけループ処理
        foreach($progress as $key => $value){
            // item_idが一致した場合
            if($value['item_id'] == $request->item_id){
                // 要素を削除
                unset($progress[$key]);
                // 処理を抜ける
                break;
            }
        }
        // 添字を振り直す
        $progress = array_values($progress);
        // セッションへ戻す
        session(['progress' => $progress]);
    }
}