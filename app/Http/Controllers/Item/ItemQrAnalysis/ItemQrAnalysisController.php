<?php

namespace App\Http\Controllers\Item\ItemQrAnalysis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\ItemQrAnalysisHistory;
// リクエスト
use App\Http\Requests\Item\ItemQrAnalysis\ItemQrAnalysisRequest;
// その他
use Illuminate\Support\Facades\Auth;

class ItemQrAnalysisController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '商品QR解析']);
        // 商品QR解析履歴を取得(直近50件のみ取得)
        $item_qr_analysis_histories = ItemQrAnalysisHistory::getAll()->get();
        return view('item.item_qr_analysis.index', compact('item_qr_analysis_histories'));
    }

    public function analysis(ItemQrAnalysisRequest $request)
    {
        // 変数に入力された値を格納
        $qr         = $request->qr_code;
        $jan_code   = $request->jan_code;
        $lot        = $request->lot;
        // DB保存用パラメータ
        $saveParam = [
            'qr_code'  => substr($qr, 0, 50),
            'jan_code' => $jan_code,
            'lot'      => $lot,
            'user_no'  => Auth::user()->user_no,
        ];
        // ── JANコード一致チェック（先頭13桁）
        if($jan_code !== ''){
            $qrJan = substr($qr, 0, 13);
            $match = $qrJan === $jan_code;
            $saveParam['is_jan_code_match'] = $match;
        }
        // ── 度数計算（30桁目以降を後ろから検索）
        if(strlen($qr) >= 30){
            $searchArea = substr($qr, 29);
            $foundCode  = null;
            $foundPos   = null;
            for($i = strlen($searchArea) - 3; $i >= 0; $i--){
                $chunk = substr($searchArea, $i, 3);
                if(ctype_digit($chunk)){
                    $num = (int) $chunk;
                    if($num >= 188 && $num <= 248){
                        $foundCode = $num;
                        $foundPos  = 30 + $i; // 1-based
                        break;
                    }
                }
            }
            if($foundCode !== null){
                $power = $foundCode * 0.25 - 50;
                $saveParam['s_power_code']                  = $foundCode;
                $saveParam['s_power_code_start_position']   = $foundPos;
                if ($foundCode < 200) {
                    $saveParam['power'] = '+' . number_format(abs($power), 2);
                } elseif ($foundCode > 200) {
                    $saveParam['power'] = '-' . number_format(abs($power), 2);
                } else {
                    $saveParam['power'] = '±' . number_format(abs($power), 2);
                }
            }
        }
        // ── LOTがQRに含まれているかチェック
        if($lot !== ''){
            $pos = strpos($qr, $lot);
            if($pos !== false){
                $saveParam['is_lot_match']       = true;
                $saveParam['lot_start_position'] = $pos + 1;
                $saveParam['lot_length']         = strlen($lot);
            }else{
                $saveParam['is_lot_match'] = false;
            }
        }
        $expRaw = substr($qr, 15, 4); // 16桁目から4桁（0-index: 15）
        $mm = substr($expRaw, 2, 2);
        if(ctype_digit($expRaw) && (int)$mm >= 1 && (int)$mm <= 12){
            $saveParam['exp']                = '20' . $expRaw; // yymm → yyyymm
            $saveParam['exp_start_position'] = 16;
        }
        // ── DBに保存
        ItemQrAnalysisHistory::create($saveParam);
        return redirect()->route('item_qr_analysis.index')->with([
            'alert_type' => 'success',
            'alert_message' => 'QR解析が完了しました。',
        ]);
    }
}