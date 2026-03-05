<?php

namespace App\Http\Controllers\Setting\Shipper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Shipper;
// サービス
use App\Services\Setting\Shipper\ShipperUpdateService;
// リクエスト
use App\Http\Requests\Setting\Shipper\ShipperUpdateRequest;
// その他
use Illuminate\Support\Facades\DB;

class ShipperUpdateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '荷送人更新']);
        // 荷送人を取得
        $shipper = Shipper::getSpecify($request->shipper_id)->first();
        return view('setting.shipper.update')->with([
            'shipper' => $shipper,
        ]);
    }

    public function update(ShipperUpdateRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $ShipperUpdateService = new ShipperUpdateService;
                // 荷送人を更新
                $ShipperUpdateService->updateShipper($request);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => '荷送人の更新に失敗しました。',
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '荷送人を更新しました。',
        ]);
    }
}