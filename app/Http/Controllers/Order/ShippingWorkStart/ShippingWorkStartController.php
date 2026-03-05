<?php

namespace App\Http\Controllers\Order\ShippingWorkStart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// リクエスト
use App\Http\Requests\Order\ShippingWorkStart\ShippingWorkStartRequest;
// サービス
use App\Services\Order\ShippingWorkStart\ShippingWorkStartService;
use App\Services\Common\MieruService;
// その他
use Illuminate\Support\Facades\DB;

class ShippingWorkStartController extends Controller
{
    public function enter(Request $request)
    {
        try{
            $count = DB::transaction(function () use ($request){
                // インスタンス化
                $ShippingWorkStartService = new ShippingWorkStartService;
                // 選択している対象が出荷開始できるか確認
                $ShippingWorkStartService->checkShippingWorkStartable($request->chk);
                // 出荷グループを作成
                $shipping_group = $ShippingWorkStartService->createShippingGroup($request);
                // 出荷グループと注文ステータスを更新
                return $ShippingWorkStartService->updateShippingWorkStart($request->chk, $shipping_group->shipping_group_id);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        // インスタンス化
        $MieruService = new MieruService;
        // ミエルの進捗を更新する対象を取得
        $MieruService->getUpdateProgressTarget(null);
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => $count . '件の出荷作業を開始しました。',
        ]);
    }
}