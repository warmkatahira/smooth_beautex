<?php

namespace App\Services\Shipping\ShippingInspection;

// サービス
use App\Services\Shipping\ShippingInspection\LotUpdateService;
// 列挙
use App\Enums\InspectionEnum;
// その他
use Carbon\CarbonImmutable;

class ItemIdCodeCheckService
{
    // 検品対象の商品か確認し、問題なければ検品数をカウントアップ
    public function check($request)
    {
        // セッションの中身を配列にセット
        $progress = session('progress');
        // セッションを初期化
        session(['model_jan_match' => false]);              // 代表JANコードが一致したかを判断
        session(['found' => false]);                        // 商品が見つかったか判断
        session(['inspection' => false]);                   // 検品できたか判断
        session(['item_id' => null]);                       // 検品したitem_id
        session(['order_item_id' => null]);                 // 検品したorder_item_id
        session(['inspection_quantity' => null]);           // カウントアップした後の検品数
        session(['inspection_complete' => false]);          // 検品数をカウントアップした商品の検品が完了したか判断
        session(['inspection_complete_order' => false]);    // 受注内の全ての商品で検品が完了しているか判断
        session(['error_message' => null]);                 // エラーメッセージを格納
        session(['exp_lot_check_result' => null]);          // 使用期限/Lotの確認結果を格納
        session(['exp' => null]);                           // 使用期限を格納
        session(['item_id_type' => null]);                  // JANコードかQRコードかを格納
        // JANコードかQRコードか判定
        // JANの桁数以下の場合
        if(strlen($request->item_id_code) <= InspectionEnum::JAN_LENGTH){
            // JANを格納
            session(['item_id_type' => 'JAN']);
            // 検品対象の商品があるかチェック
            $this->checkJanCode($progress, $request->item_id_code, $request->order_control_id);
        }
        // JANの桁数より大きい場合
        if(strlen($request->item_id_code) > InspectionEnum::JAN_LENGTH){
            // QRを格納
            session(['item_id_type' => 'QR']);
            // 検品対象の商品があるかチェック
            $this->checkQrCode($progress, $request->item_id_code);
            // 商品が見つかっていたら
            if(session('found') && session('order_item_id')){
                // 検品対象が取得できていて、exp_start_positionがnull以外である
                if(!is_null(session('order_item_id')) && !is_null($progress[session('order_item_id')]['exp_start_position'])){
                    // 使用期限のチェック
                    $exp = $this->checkExp($request->item_id_code, $progress[session('order_item_id')]['exp_start_position']);
                }
                // 使用期限のチェックが問題なければ
                if(is_null(session('exp_lot_check_result'))){
                    // インスタンス化
                    $LotUpdateService = new LotUpdateService;
                    // QRコードからLOTを取得
                    $lot = $this->getLotQr($progress, session('order_item_id'), $request->item_id_code);
                    // lotがNull以外の場合
                    if(!is_null($lot)){
                        // Lotの配列を更新
                        $LotUpdateService->updateLotResult($lot, $request->order_control_id);
                        // 検品数をカウントアップ
                        $this->updateInspectionQuantity($progress, session('order_item_id'));
                    }
                    // lotがNullの場合
                    if(is_null($lot)){
                        session(['exp_lot_check_result' => 'Lotが取得できませんでした。']);
                    }
                }
            }
        }
        // エラーがあったか確認(inspectionがtrueであれば、検品できているので、nullを返す)
        session(['error_message' => session('inspection') ? null : $this->checkError($request->item_id_code)]);
        return;
    }

    // 検品対象の商品があるかチェック
    public function checkJanCode($progress, $item_id_code, $order_control_id)
    {
        // 配列の分だけループ処理
        foreach($progress as $key => $value){
            // JANコードが一致している場合
            if($value['item_jan_code'] == $item_id_code){
                // フラグをtrueにする
                session(['found' => true]);
            }
            // JANコードが一致しているかつ、検品できる商品であるか
            if($value['item_jan_code'] == $item_id_code && $value['order_quantity'] > $value['inspection_quantity']){
                // 特定した商品IDを取得
                session(['item_id' => $value['item_id']]);
                // 特定した配列のキーを取得
                session(['order_item_id' => $key]);
                // LOT1開始位置がNullの場合
                if(is_null($value['lot_1_start_position'])){
                    // インスタンス化
                    $LotUpdateService = new LotUpdateService;
                    // 「-」をLotとしてセット
                    $lot = '-';
                    // Lotの配列を更新
                    $LotUpdateService->updateLotResult($lot, $order_control_id);
                    // 検品数をカウントアップ
                    $this->updateInspectionQuantity($progress, session('order_item_id'));
                }
                break;
            }
        }
    }

    // 検品対象の商品があるかチェック
    public function checkQrCode($progress, $item_id_code)
    {
        // 代表JANコードがnull以外の情報を取得
        $model_jan_code_arr = array_filter($progress, function ($item) {
            return $item['model_jan_code'] !== null;
        });
        // 代表JANコードがnull以外の情報があれば、代表JANコードの処理を実施
        if(count($model_jan_code_arr) > 0) {
            // 代表JANコードが設定されている商品をループ処理
            foreach($model_jan_code_arr as $key => $value){
                // 代表JANが一致したかを確認(一致していたら、この後にある個別JANチェックを行わないようにする)
                if($value['model_jan_code'] == substr($item_id_code, 0, InspectionEnum::JAN_LENGTH)){
                    session(['model_jan_match' => true]);
                }
                // 代表JANコードとS-POWERコードが一致していたら(-1しているのは、0から数え始める為)
                if($value['model_jan_code'] == substr($item_id_code, 0, InspectionEnum::JAN_LENGTH) && $value['s_power_code'] == substr($item_id_code, $value['s_power_code_start_position'] - 1, InspectionEnum::S_POWER_CODE_LENGTH)){
                    session(['found' => true]);
                }
                // 代表JANコードとS-POWERコードが一致しているかつ、検品できる商品であるか
                if($value['model_jan_code'] == substr($item_id_code, 0, InspectionEnum::JAN_LENGTH) && $value['s_power_code'] == substr($item_id_code, $value['s_power_code_start_position'] - 1, InspectionEnum::S_POWER_CODE_LENGTH) && $value['order_quantity'] > $value['inspection_quantity']){
                    // 特定した商品IDを取得
                    session(['item_id' => $value['item_id']]);
                    // 特定した配列のキーを取得
                    session(['order_item_id' => $key]);
                    break;
                }
            }
        }
        // 検品できる商品が見つかっていないかつ、代表JANが一致していない場合
        if(is_null(session('order_item_id')) && !session('model_jan_match')){
            // 商品識別コードの先頭13桁で照合
            foreach($progress as $key => $value){
                // JANコードが一致していたらフラグをtrueにする
                if($value['item_jan_code'] == substr($item_id_code, 0, InspectionEnum::JAN_LENGTH)){
                    session(['found' => true]);
                }
                // JANコードが一致しているかつ、検品できる商品であるか
                if($value['item_jan_code'] == substr($item_id_code, 0, InspectionEnum::JAN_LENGTH) && $value['order_quantity'] > $value['inspection_quantity']){
                    // 特定した商品IDを取得
                    session(['item_id' => $value['item_id']]);
                    // 特定した配列のキーを取得
                    session(['order_item_id' => $key]);
                    break;
                }
            }
        }
    }

    // 使用期限のチェック
    public function checkExp($item_id_code, $exp_start_position)
    {
        // 商品識別コードからEXPを取得し、先頭に「20」を付けて、yyyymmの形式にする(-1しているのは、0から数え始める為)
        $exp = '20' . substr($item_id_code, $exp_start_position - 1, InspectionEnum::EXP_LENGTH);
        // セッションにEXPを格納
        session(['exp' => $exp]);
        // 連続した数値であるかチェック
        if(!preg_match('/^\d{6}$/', $exp)){
            session(['exp_lot_check_result' => '使用期限に数値以外が存在しています。<br>' . $exp]);
            return;
        }
        // 年月を取得
        $year = substr($exp, 0, 4);
        $month = substr($exp, 4, 2);
        // 日付が有効かどうかを確認する
        if(!checkdate($month, '01', $year)){
            session(['exp_lot_check_result' => '使用期限が日付ではありません。<br>' . $exp]);
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
            session(['exp_lot_check_result' => '出荷できない使用期限です。<br>' . $exp . '<br><br>出荷可能使用期限：'.$shipping_available]);
            return;
        }
        return $exp;
    }

    // QRコードからLOTを取得
    public function getLotQr($progress, $key, $item_id_code)
    {
        // 変数を初期化
        $lot_1 = '';
        $lot_2 = '';
        // Lot1開始位置がNullの場合
        if(is_null($progress[$key]['lot_1_start_position'])){
            return null;
        }
        // LOT1の設定で取得(-1しているのは、0から数え始める為)
        $lot_1 = substr($item_id_code, $progress[$key]['lot_1_start_position'] - 1, $progress[$key]['lot_1_length']);
        // LOT2の設定で取得(-1しているのは、0から数え始める為)
        // 設定がnullではない場合
        if(!is_null($progress[$key]['lot_2_start_position'])){
            $lot_2 = substr($item_id_code, $progress[$key]['lot_2_start_position'] - 1, $progress[$key]['lot_2_length']);
        }
        return $lot_1 . $lot_2;
    }

    // 検品数をカウントアップ
    public function updateInspectionQuantity($progress, $key)
    {
        // 検品数を+1
        $progress[$key]['inspection_quantity'] = (int)$progress[$key]['inspection_quantity'] + 1;
        // 出荷数 = 検品数であれば、検品完了なのでtrueにする
        $progress[$key]['inspection_complete'] = $progress[$key]['inspection_quantity'] == $progress[$key]['order_quantity'] ? true : false;
        // 検品できたので、trueにする
        session(['inspection' => true]);
        // セッションへ戻す
        session(['progress' => $progress]);
        // テーブル更新に使用する情報を格納
        session(['inspection_quantity' => $progress[$key]['inspection_quantity']]);
        session(['inspection_complete' => $progress[$key]['inspection_complete']]);
        // 受注内の全ての商品で検品が完了しているか確認
        session(['inspection_complete_order' => $this->checkInspectionCompleteOrder($progress)]);
        return;
    }

    // 受注内の全ての商品で検品が完了しているか確認
    public function checkInspectionCompleteOrder($progress)
    {
        // 配列の分だけループ処理
        foreach($progress as $key => $value){
            // 1つでもfalseがあれば、検品が完了していないので、falseを返す
            if($value['inspection_complete'] == false) {
                return false;
                break;
            }
        }
        // 全て完了しているので、trueを返す
        return true;
    }

    // エラーがあったか確認
    public function checkError($item_id_code)
    {
        // 商品が見つけられていない
        if(!session('found')){
            return '検品対象外の商品です。<br>' . $item_id_code;
        }
        // JANコードの場合
        if(session('item_id_type') === 'JAN'){
            // 検品対象ではない
            if(!session('order_item_id')){
                return '検品が完了しています。<br>' . $item_id_code;
            }
            return;
        }
        // 以降はQRコードの確認
        // 使用期限/Lotチェックで問題あり
        if(!is_null(session('exp_lot_check_result'))){
            return session('exp_lot_check_result');
        }
        // 検品ができていない
        if(!session('inspection')){
            return '検品が完了しています。<br>' . $item_id_code;
        }
        return null;
    }
}