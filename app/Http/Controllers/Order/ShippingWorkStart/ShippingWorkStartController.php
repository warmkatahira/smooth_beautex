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
    public function enter(ShippingWorkStartRequest $request)
    {
        try{
            $result = DB::transaction(function () use ($request){
                // インスタンス化
                $ShippingWorkStartService = new ShippingWorkStartService;
                // 選択している対象が出荷開始できるか確認
                $orders = $ShippingWorkStartService->checkShippingWorkStartable($request->chk);
                // 出荷グループを作成
                $shipping_group = $ShippingWorkStartService->createShippingGroup($request);
                // 出荷グループと注文ステータスを更新
                $count = $ShippingWorkStartService->updateShippingWorkStart($request->chk, $shipping_group->shipping_group_id);
                // 配送方法がEMSでUS宛ての場合、出荷個口Noを条件に応じて更新
                $over_threshold_order_ids = $ShippingWorkStartService->updatePackageNo($orders);
                return compact('shipping_group', 'count', 'over_threshold_order_ids');
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
        // メッセージを作成
        $message = $result['count'] . '件の出荷作業を開始しました。';
        $alert_type = 'success';
        // 配列が空ではない場合
        if(!empty($result['over_threshold_order_ids'])){
            $message .= "\n1商品で14,500円をこえる受注が" . count($result['over_threshold_order_ids']) . "件あります。";
            $alert_type = 'warning';
        }
        return redirect()->back()->with([
            'alert_type' => $alert_type,
            'alert_message' => $message,
        ]);
    }
}