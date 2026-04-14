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
        session(['add' => false]);                          // 新しい商品がスキャンされたかを判断
        session(['quantity' => 1]);                         // 今回スキャンした商品の数量を格納
        session(['exp_lot_check_result' => null]);          // 使用期限/Lotの確認結果を格納
        session(['exp' => null]);                           // 使用期限を格納
        session(['lot' => null]);                           // LOTを格納
        session(['item_id_type' => null]);                  // JANコードかQRコードかを格納
        session(['error_message' => null]);                 // エラーメッセージを格納
        session(['item' => null]);                          // 特定した商品情報を格納
        // JANコードかQRコードか判定
        // JANの桁数以下の場合
        if(strlen($request->item_id_code) <= InspectionEnum::JAN_LENGTH){
            // JANを格納
            session(['item_id_type' => 'JAN']);
            // JANコードを使って商品マスタからレコードを取得
            $this->getItemFromJanCode($request->item_id_code);
            // ロット管理が無効の場合
            if(session('found') && !session('item')->is_lot_managed){
                session(['lot' => null]);
                session(['exp' => null]);
                $this->setScanInfo();
            }
        }
        // JANの桁数より大きい場合
        if(strlen($request->item_id_code) > InspectionEnum::JAN_LENGTH){
            // QRを格納
            session(['item_id_type' => 'QR']);
            // QRコードを使って商品マスタからレコードを取得
            $this->getItemFromQrCode($request->item_id_code);
            // 商品が見つかっていたら
            if(session('found')){
                // 変数を初期化
                $exp = null;
                // exp_start_positionがnull以外である
                if(!is_null(session('item')->exp_start_position)){
                    // 使用期限のチェック
                    $exp = $this->checkExp($request->item_id_code, session('item')->exp_start_position);
                }
                // 使用期限のチェックが問題なければ
                if(is_null(session('exp_lot_check_result'))){
                    // QRコードからLOTを取得
                    $this->getLotQr($request->item_id_code);
                    // lotがNull以外の場合
                    if(!is_null(session('lot'))){
                        $this->setScanInfo();
                    }
                    // lotがNullの場合
                    if(is_null(session('lot'))){
                        session(['exp_lot_check_result' => 'LOTが取得できませんでした。']);
                    }
                }
            }
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
            session(['item' => $item]);
        }
    }

    // QRコードを使って商品マスタからレコードを取得
    public function getItemFromQrCode($item_id_code)
    {
        /* // 商品を全て取得し配列に変換
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
                    session(['item' => Item::getSpecify($value['item_id'])->first()]);
                    break;
                }
            }
        } */
        $model_jan_code = substr($item_id_code, 0, InspectionEnum::JAN_LENGTH);
        // ★ 全件取得をやめて、代表JANコードが一致するものだけ取得
        $items = Item::where('model_jan_code', $model_jan_code)->get();
        if($items->isNotEmpty()) {
            session(['model_jan_match' => true]);

            foreach($items as $item) {
                // S-POWERコードが一致したら
                if($item->s_power_code == substr($item_id_code, $item->s_power_code_start_position - 1, InspectionEnum::S_POWER_CODE_LENGTH)) {
                    session(['found' => true]);
                    session(['item_id' => $item->item_id]);
                    session(['item' => $item]);
                    return;
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
    }

    // QRコードからLOTを取得
    public function getLotQr($item_id_code)
    {
        // 変数を初期化
        $lot_1 = '';
        $lot_2 = '';
        // Lot1開始位置がNullの場合
        if(is_null(session('item')->lot_1_start_position)){
            return null;
        }
        // LOT1の設定で取得(-1しているのは、0から数え始める為)
        $lot_1 = substr($item_id_code, session('item')->lot_1_start_position - 1, session('item')->lot_1_length);
        // LOT2の設定で取得(-1しているのは、0から数え始める為)
        // 設定がnullではない場合
        if(!is_null(session('item')->lot_2_start_position)){
            $lot_2 = substr($item_id_code, session('item')->lot_2_start_position - 1, session('item')->lot_2_length);
        }
        session(['lot' => $lot_1.$lot_2]);
    }

    // 検品情報を配列に格納
    public function setScanInfo()
    {
        // セッションの中身を配列にセット
        $progress = session('progress', []);
        // item_id・lot・expを組み合わせてキーを生成
        $key = session('item_id') . '★' . session('lot') . '★' . session('exp');
        // 同じキーが存在すれば数量を+1、なければ追加
        if(array_key_exists($key, $progress)){
            $progress[$key]['quantity']++;
        } else {
            $progress[$key] = [
                'item_id'  => session('item_id'),
                'lot'      => session('lot'),
                'exp'      => session('exp'),
                'quantity' => 1,
            ];
            session(['add' => true]);
        }
        session(['quantity' => $progress[$key]['quantity']]);
        session(['progress' => $progress]);
    }
}