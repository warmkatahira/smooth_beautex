<?php

namespace App\Http\Controllers\Stock\ReceivingInspection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Base;
// サービス
use App\Services\Stock\ReceivingInspection\ItemIdCodeCheckService;
use App\Services\Stock\ReceivingInspection\LotExpCheckService;

class ReceivingInspectionController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '入庫検品']);
        // セッションを初期化
        session(['progress' => array()]);
        // 倉庫を取得
        $bases = Base::getAll()->get();
        return view('stock.receiving_inspection.index')->with([
            'bases' => $bases,
        ]);
    }

    // 商品識別コードが変更された際の処理
    public function ajax_check_item_id_code(Request $request)
    {
        // インスタンス化
        $ItemIdCodeCheckService = new ItemIdCodeCheckService;
        // 商品マスタから検品した商品を特定
        $ItemIdCodeCheckService->check($request);
        // 商品が見つかっていない場合
        if(!session('found')){
            session(['error_message' => '商品が見つかりませんでした。<br>' . $request->item_id_code]);
        }
        // 結果を返す
        return response()->json([
            'error_message' => session('error_message'),
            'exp_lot_check_result' => session('exp_lot_check_result'),
            'add' => session('add'),
            'quantity' => session('quantity'),
            'item' => session('item'),
            'quantity' => session('quantity'),
            'lot' => session('lot'),
            'exp' => session('exp'),
            'item_id_type' => session('item_id_type'),
        ]);
    }

    // LOTとEXPが入力された際の処理
    public function ajax_check_lot_exp(Request $request)
    {
        // インスタンス化
        $LotExpCheckService = new LotExpCheckService;
        // LOTとEXPを確認
        $LotExpCheckService->check($request->lot, $request->exp);
        // 結果を返す
        return response()->json([
            'error_message' => session('error_message'),
            'add' => session('add'),
            'quantity' => session('quantity'),
            'progress' => session('progress'),
            'lot' => session('lot'),
            'exp' => session('exp'),
            'item' => session('item'),
        ]);
    }
}