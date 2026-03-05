<?php

namespace App\Services\Shipping\ShippingInspection;

// サービス
use App\Services\Shipping\ShippingInspection\ItemIdCodeCheckService;
use App\Services\Shipping\ShippingInspection\LotUpdateService;

class LotCheckService
{
    public function check($lot, $order_control_id)
    {
        // インスタンス化
        $LotUpdateService = new LotUpdateService;
        $ItemIdCodeCheckService = new ItemIdCodeCheckService;
        // セッションの中身を配列にセット
        $progress = session('progress');
        // LOTが正しいか桁数をチェック
        $this->checkLotLength($lot);
        // エラーがなければ処理を実行
        if(is_null(session('error_message'))){
            // LOTの配列を更新
            $LotUpdateService->updateLotResult($lot, $order_control_id);
            // 検品数をカウントアップ
            $ItemIdCodeCheckService->updateInspectionQuantity($progress, session('order_item_id'));
        }
    }

    // Lot桁数を確認
    public function checkLotLength($lot)
    {
        // セッションの中身を配列にセット
        $progress = session('progress');
        // Lot桁数を取得
        $lot_1_length = $progress[session('order_item_id')]['lot_1_length'];
        $lot_2_length = $progress[session('order_item_id')]['lot_2_length'] ?? 0;
        // Lot桁数が一致していない場合
        if(strlen($lot) != $lot_1_length + $lot_2_length){
            session(['error_message' => 'LOT桁数が正しくありません。']);
        }
    }

    // LOTの配列を更新
    public function updateLotResult($lot, $order_control_id)
    {
        // セッションの中身を配列にセット
        $lot_result = session('lot_result');
        // 検品対象商品の配列がある場合
        if(array_key_exists(session('order_detail_id'), $lot_result)) {
            // 同じLOTが存在したか判定する変数
            $exsits = false;
            // 配列の分だけループ処理
            foreach($lot_result[session('order_detail_id')] as $key => $value){
                // 同じLOTが存在すれば、数量を+1する
                if($value['lot'] == $lot){
                    $exsits = true;
                    $lot_result[session('order_detail_id')][$key]['quantity'] = (int)$lot_result[session('order_detail_id')][$key]['quantity'] + 1;
                    break;
                }
            }
            // 同じLOTが無ければ、配列に追加する
            if(!$exsits){
                $lot_result = $this->insertLotResult($lot_result, $lot, $order_control_id);
            }
        }
        // 検品対象商品の配列がない場合
        if(!array_key_exists(session('order_detail_id'), $lot_result)) {
            $lot_result[session('order_detail_id')] = [];
            $lot_result = $this->insertLotResult($lot_result, $lot, $order_control_id);
        }
        // セッションへ戻す
        session(['lot_result' => $lot_result]);
        return;
    }

    // 配列へLOTを追加
    public function insertLotResult($lot_result, $lot, $order_control_id)
    {
        array_push($lot_result[session('order_detail_id')], [
            'order_control_id' => $order_control_id,
            'order_detail_id' => session('order_detail_id'),
            'lot' => $lot,
            'quantity' => 1,
        ]);
        return $lot_result;
    }
}