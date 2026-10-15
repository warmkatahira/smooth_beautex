<?php

namespace App\Http\Controllers\Order\OrderItem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// リクエスト
use App\Http\Requests\Order\OrderItem\OrderItemAllocateReleaseRequest;
// サービス
use App\Services\Order\OrderItem\OrderItemAllocateReleaseService;
use App\Services\Common\MieruService;
// その他
use Illuminate\Support\Facades\DB;

class OrderItemAllocateReleaseController extends Controller
{
    public function release(OrderItemAllocateReleaseRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // インスタンス化
                $OrderItemAllocateReleaseService = new OrderItemAllocateReleaseService;
                // 引当解除できる受注商品であるか確認
                [$order, $order_item] = $OrderItemAllocateReleaseService->checkAllocateReleasable($request->order_item_id);
                // 引当解除
                $OrderItemAllocateReleaseService->releaseAllocate($order, $order_item);
            });
        } catch (\Exception $e) {
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
            'alert_message' => '引当を解除しました。',
        ]);
    }
}
