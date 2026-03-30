<?php

namespace App\Services\Stock\ReceivingInspection;

// サービス
use App\Services\Stock\ReceivingInspection\ItemIdCodeCheckService;
// 列挙
use App\Enums\InspectionEnum;
// その他
use Carbon\CarbonImmutable;

class LotExpCheckService
{
    // LOTとEXPを確認
    public function check($lot, $exp)
    {
        // error_messageを初期化
        session(['error_message' => null]);
        // インスタンス化
        $ItemIdCodeCheckService = new ItemIdCodeCheckService;
        // LOTが正しいか桁数をチェック
        $this->checkLotLength($lot);
        // EXPが正しいかチェック
        $this->checkExp($exp);
        // エラーがなければ処理を実行
        if(is_null(session('error_message'))){
            // 検品情報を配列に格納
            $ItemIdCodeCheckService->setScanInfo();
        }
    }

    // Lot桁数を確認
    public function checkLotLength($lot)
    {
        // Lot桁数を取得
        $lot_1_length = session('item')->lot_1_length;
        $lot_2_length = session('item')->lot_2_length ?? 0;
        // Lot桁数が一致していない場合
        if(strlen($lot) != $lot_1_length + $lot_2_length){
            session(['error_message' => 'LOT桁数が正しくありません。']);
            return;
        }
        // セッションにLOTを格納
        session(['lot' => $lot]);
    }

    // EXPが正しいか確認
    public function checkExp($exp)
    {
        // 連続した数値であるかチェック
        if(!preg_match('/^\d{6}$/', $exp)){
            session(['error_message' => '使用期限に数値以外が存在しています。']);
            return;
        }
        // 年月を取得
        $year = substr($exp, 0, 4);
        $month = substr($exp, 4, 2);
        // 日付が有効かどうかを確認する
        if(!checkdate($month, '01', $year)){
            session(['error_message' => '使用期限が日付ではありません。']);
            return;
        }
        // セッションにEXPを格納
        session(['exp' => $exp]);
    }
}