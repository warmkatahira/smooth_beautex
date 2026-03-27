<?php

namespace App\Services\Shipping\ShippingInspection;

// サービス
use App\Services\Shipping\ShippingInspection\ItemIdCodeCheckService;
use App\Services\Shipping\ShippingInspection\LotUpdateService;
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
        $LotUpdateService = new LotUpdateService;
        $ItemIdCodeCheckService = new ItemIdCodeCheckService;
        // セッションの中身を配列にセット
        $progress = session('progress');
        // LOTが正しいか桁数をチェック
        $this->checkLotLength($lot);
        // EXPが正しいかチェック
        $this->checkExp($exp);
        // エラーがなければ処理を実行
        if(is_null(session('error_message'))){
            // Lotの配列を更新
            $LotUpdateService->updateLotResult($lot, $exp);
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
        // QRのEXPから閾値の月数を引く
        $exp_threshold = CarbonImmutable::createFromFormat('Ym', $exp)->subMonths(InspectionEnum::EXP_THRESHOLD);
        $exp_threshold = $exp_threshold->format('Ym');
        // 現在の日付を取得
        $now = CarbonImmutable::now()->format('Ym');
        // 出荷可能な年月を算出
        $shipping_available = CarbonImmutable::now()->addMonths(InspectionEnum::EXP_THRESHOLD + 1)->format('Y/m');
        // 閾値を引いた日付が現在の日付よりも大きいか
        if($now >= $exp_threshold){
            session(['error_message' => '出荷できない使用期限です。']);
            return;
        }
    }
}