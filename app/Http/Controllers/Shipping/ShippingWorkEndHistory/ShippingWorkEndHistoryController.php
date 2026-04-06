<?php

namespace App\Http\Controllers\Shipping\ShippingWorkEndHistory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\ShippingWorkEndHistory;

class ShippingWorkEndHistoryController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '出荷完了履歴']);
        // 出荷完了履歴を取得
        $shipping_work_end_histories = ShippingWorkEndHistory::getDispData()->get();
        return view('shipping.shipping_work_end_history.index')->with([
            'shipping_work_end_histories' => $shipping_work_end_histories,
        ]);
    }

    public function error_download(Request $request)
    {
        // ファイル名とフルパスを変数に格納
        $filename = $request->filename;
        $path = storage_path('app/public/export/shipping_work_end_error/'.$filename);
        // ファイルが存在しない場合はエラーを返す
        if(!file_exists($path)){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => 'ファイルが存在しません。',
            ]);
        }
        // ダウンロード処理
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=' . $filename,
        ];
        return response()->download($path, $filename, $headers);
    }
}
