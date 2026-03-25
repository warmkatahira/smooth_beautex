<?php

namespace App\Http\Controllers\Order\ShippingWorkStart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// リクエスト
use App\Http\Requests\Order\ShippingWorkStart\ShippingWorkStartRequest;
// サービス
use App\Services\Order\ShippingWorkStart\ShippingWorkStartService;
use App\Services\Common\MieruService;
use App\Services\Common\ChatworkService;
// その他
use Illuminate\Support\Facades\DB;

class ShippingWorkStartController extends Controller
{
    public function enter(Request $request)
    {
        try{
            $result = DB::transaction(function () use ($request){
                // インスタンス化
                $ShippingWorkStartService = new ShippingWorkStartService;
                // 選択している対象が出荷開始できるか確認
                $ShippingWorkStartService->checkShippingWorkStartable($request->chk);
                // 出荷グループを作成
                $shipping_group = $ShippingWorkStartService->createShippingGroup($request);
                // 出荷グループと注文ステータスを更新
                $count = $ShippingWorkStartService->updateShippingWorkStart($request->chk, $shipping_group->shipping_group_id);
                return compact('shipping_group', 'count');
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        // インスタンス化
        $MieruService = new MieruService;
        $ChatworkService = new ChatworkService;
        // ミエルの進捗を更新する対象を取得
        $MieruService->getUpdateProgressTarget(null);
        // Chatworkに通知する処理
        $ChatworkService->postMessageAtSihppingWorkStart(count($request->chk), $result['shipping_group']->shipping_group_name);
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => $result['count'] . '件の出荷作業を開始しました。',
        ]);
    }
}