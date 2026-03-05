<?php

namespace App\Services\Stock\ReceivingInspection;
// モデル
use App\Models\Item;
// 列挙
use App\Enums\InspectionEnum;
// その他
use Carbon\CarbonImmutable;

class ItemIdCodeCheckService
{
    // 商品マスタに存在するか確認し、問題なければ検品数をカウントアップ
    public function check($request)
    {
        // セッションを初期化
        session(['model_jan_match' => false]);              // 代表JANコードが一致したかを判断
        session(['found' => false]);                        // 商品が見つかったか判断
        session(['item_id' => null]);                       // 検品した商品ID
        session(['item_code' => null]);                     // 検品した商品コード
        session(['item_jan_code' => null]);                 // 検品した商品JANコード
        session(['item_name' => null]);                     // 検品した商品名
        session(['exp_start_position' => null]);            // 検品した商品のEXP開始位置
        session(['add' => false]);                          // 新しい商品がスキャンされたかを判断
        session(['quantity' => 1]);                         // 今回スキャンした商品の数量を格納
        session(['exp_check_result' => null]);              // 使用期限の確認結果を格納
        session(['exp' => null]);                           // 使用期限を格納
        session(['item_id_type' => null]);                  // JANコードかQRコードかを格納
        session(['error_message' => null]);                 // エラーメッセージを格納
        // JANコードかQRコードか判定
        // JANの桁数以下の場合
        if(strlen($request->item_id_code) <= InspectionEnum::JAN_LENGTH){
            // JANを格納
            session(['item_id_type' => 'JAN']);
            // JANコードを使って商品マスタからレコードを取得
            $this->getItemFromJanCode($request->item_id_code);
        }
        // JANの桁数より大きい場合
        if(strlen($request->item_id_code) > InspectionEnum::JAN_LENGTH){
            // QRを格納
            session(['item_id_type' => 'QR']);
            // QRコードを使って商品マスタからレコードを取得
            $this->getItemFromQrCode($request->item_id_code);
        }
    }

    // JANコードを使って商品マスタからレコードを取得
    public function getItemFromJanCode($item_id_code)
    {
        // 商品JANコードを条件に商品マスタからレコードを取得
        $item = Item::where('item_jan_code', $item_id_code)->first();
        // レコードが取得できている場合
        if(!is_null($item)){
            session(['found' => true]);
            // 特定した商品を取得
            session(['item_id' => $item->item_id]);
            session(['item_code' => $item->item_code]);
            session(['item_jan_code' => $item->item_jan_code]);
            session(['item_name' => $item->item_name]);
            session(['exp_start_position' => $item->exp_start_position]);
        }
    }

    // QRコードを使って商品マスタからレコードを取得
    public function getItemFromQrCode($item_id_code)
    {
        // 商品を全て取得し配列に変換
        $items = Item::getAll()->get()->toArray();
        // 代表JANコードがnull以外の情報を取得
        $model_jan_code_arr = array_filter($items, function ($item) {
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
                    // 特定した商品を取得
                    session(['item_id' => $value['item_id']]);
                    session(['item_code' => $value['item_code']]);
                    session(['item_jan_code' => $value['item_jan_code']]);
                    session(['item_name' => $value['item_name']]);
                    session(['exp_start_position' => $value['exp_start_position']]);
                    break;
                }
            }
        }
        // 商品が見つかっていないかつ、代表JANが一致していない場合
        if(!session('found') && !session('model_jan_match')){
            // JANコードを使って商品マスタからレコードを取得
            $this->getItemFromJanCode(substr($item_id_code, 0, 13));
        }
    }

    // 商品識別コードからEXPを取得し、チェック
    public function checkExp($item_id_code)
    {
        // item_id_typeがJANであれば処理をスキップ
        if(session('item_id_type') === 'JAN'){
            return;
        }
        // 商品識別コードからEXPを取得し、先頭に「20」を付けて、yyyymmの形式にする(-1しているのは、0から数え始める為)
        $exp = '20' . substr($item_id_code, session('exp_start_position') - 1, InspectionEnum::EXP_LENGTH);
        // セッションにEXPを格納
        session(['exp' => $exp]);
        // 連続した数値であるかチェック
        if(!preg_match('/^\d{6}$/', $exp)){
            session(['exp_check_result' => '使用期限に数値以外が存在しています。<br>' . $exp]);
            return;
        }
        // 年月を取得
        $year = substr($exp, 0, 4);
        $month = substr($exp, 4, 2);
        // 日付が有効かどうかを確認する
        if(!checkdate($month, '01', $year)){
            session(['exp_check_result' => '使用期限が日付ではありません。<br>' . $exp]);
            return;
        }
        // QRのEXPから閾値の月数を引く
        $exp_threshold = CarbonImmutable::createFromFormat('Ym', $exp)->subMonths(InspectionEnum::EXP_THRESHOLD);
        $exp_threshold = $exp_threshold->format('Ym');
        // 現在の日付を取得
        $now = CarbonImmutable::now()->format('Ym');
        // 入庫可能な年月を算出
        $receiving_available = CarbonImmutable::now()->addMonths(InspectionEnum::EXP_THRESHOLD + 1)->format('Y/m');
        // 閾値を引いた日付が現在の日付よりも大きいか
        if($now >= $exp_threshold){
            session(['exp_check_result' => '入庫できない使用期限です。<br>' . $exp . '<br><br>入庫可能使用期限：'.$receiving_available]);
            return;
        }
    }

    // 検品情報を配列に格納
    public function setScanInfo()
    {
        // セッションの中身を配列にセット
        $progress = session('progress');
        // 同じEXPが存在したか判定する変数
        $exsits = false;
        // key情報をセット
        session(['key' => session('item_id')]);
        // 配列の分だけループ処理
        foreach($progress as $key => $value){
            // 商品が存在すれば、数量を+1する
            if($value['key'] == session('key')){
                $exsits = true;
                session(['quantity' => (int)$progress[$key]['quantity'] + 1]);
                $progress[$key]['quantity'] = session('quantity');
                break;
            }
        }
        // 配列に商品が無ければ、配列に追加する
        if(!$exsits){
            array_push($progress, [
                'key' => session('key'),
                'item_id' => session('item_id'),
                'item_code' => session('item_code'),
                'item_jan_code' => session('item_jan_code'),
                'item_name' => session('item_name'),
                'quantity' => 1,
            ]);
            // 「true」をセット
            session(['add' => true]);
        }
        // セッションへ戻す
        session(['progress' => $progress]);
    }
}