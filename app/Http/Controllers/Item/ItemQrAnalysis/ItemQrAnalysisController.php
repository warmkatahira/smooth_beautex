<?php

namespace App\Http\Controllers\Item\ItemQrAnalysis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\ItemQrAnalysisHistroy;
// 列挙
use App\Enums\InspectionEnum;

class ItemQrAnalysisController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '商品QR解析']);
        // 商品QR解析履歴を取得
        $item_qr_analysis_histories = ItemQrAnalysisHistroy::getAll()->get();
        // 度数の格納する配列を初期化
        $power_lists = [];
        // 0.00から-10.0の間で0.25刻みでループ処理
        for($v = 0.00; $v >= -10.0; $v -= 0.25){
            // 配列に度数を格納
            $power_lists[] = number_format($v, 2, '.', '');
        }
        return view('item.item_qr_analysis.index')->with([
            'item_qr_analysis_histories' => $item_qr_analysis_histories,
            'power_lists' => $power_lists,
        ]);
    }

    public function analysis(Request $request)
    {
        // 度数からS-POWERコードを算出
        $s_power_code = intval((50 + abs($request->doari_power)) / 0.25);
        // QRのJANコードを抜き出す
        $qr_jan_code = substr($request->doari_qr, 0, InspectionEnum::JAN_LENGTH);
        // JANコードが一致しているかを判定する変数を初期化
        $jan_match = null;
        // QRのJANコードとバーコードのJANコードが入力されている場合
        if(!empty($qr_jan_code) && !empty($request->doari_jan)){
            // QRのJANコードとバーコードのJANコードが一致している場合
            if($qr_jan_code === $request->doari_jan){
                // 一致していればtrueを格納
                $jan_match = true;
            }
            // QRのJANコードとバーコードのJANコードが一致していない場合
            if($qr_jan_code != $request->doari_jan){
                // 一致していればtrueを格納
                $jan_match = false;
            }
        }
        // LOT開始位置を取得
        $lot_start_position = strpos($request->doari_qr, $request->doari_lot);
        // LOT開始位置
        if($lot_start_position){
            $lot_start_position++;
        }else{
            $lot_start_position = null;
        }
        // S-POWERコード開始位置を取得
        $s_power_code_start_position = strpos($request->doari_qr, $s_power_code);
        // LOT開始位置
        if($s_power_code_start_position){
            $s_power_code_start_position++;
        }else{
            $s_power_code_start_position = null;
        }
        // 商品QR解析履歴に追加
        ItemQrAnalysisHistroy::create([
            'doari_qr'                      => $request->doari_qr,
            'doari_jan'                     => $request->doari_jan,
            'doari_lot'                     => $request->doari_lot,
            'doari_power'                   => $request->doari_power,
            'item_type'                     => is_null($jan_match) ? null : ($jan_match ? '個別JAN' : '代表JAN'),
            'lot_start_position'            => $lot_start_position,
            's_power_code'                  => $s_power_code,
            's_power_code_start_position'   => $s_power_code_start_position,
            'message'                       => '',
        ]);
        return redirect()->route('item_qr_analysis.index')->with([
            'alert_type' => 'success',
            'alert_message' => 'ok',
        ]);
    }
}