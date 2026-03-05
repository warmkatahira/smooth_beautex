<?php

namespace App\Http\Controllers\Order\OrderDetail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Order\OrderDetail\OrderDetailUpdateService;
use App\Services\Order\OrderAllocate\OrderAllocateService;
// リクエスト
use App\Http\Requests\Order\OrderDetail\ShippingBaseUpdateRequest;
use App\Http\Requests\Order\OrderDetail\ShippingMethodUpdateRequest;
use App\Http\Requests\Order\OrderDetail\TrackingNoUpdateRequest;
use App\Http\Requests\Order\OrderDetail\OrderMemoUpdateRequest;
use App\Http\Requests\Order\OrderDetail\ShippingWorkMemoUpdateRequest;
use App\Http\Requests\Order\OrderDetail\DesiredDeliveryDateUpdateRequest;
// その他
use Illuminate\Support\Facades\DB;

class OrderDetailUpdateController extends Controller
{
    public function shipping_base(ShippingBaseUpdateRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $OrderDetailUpdateService = new OrderDetailUpdateService;
                // 受注をロックして取得
                $order = $OrderDetailUpdateService->getOrder($request);
                // 出荷倉庫を更新できるか確認
                $OrderDetailUpdateService->checkUpdatableShippingBase($order);
                // 引当済みの在庫数を戻す
                $OrderDetailUpdateService->incrementAllocatedStockBackToAvailableStock($order);
                // 出荷倉庫を更新
                $OrderDetailUpdateService->updateShippingBase($request, $order);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        // インスタンス化
        $OrderAllocateService = new OrderAllocateService;
        // 引当処理
        $OrderAllocateService->procOrderAllocate($request->order_control_id);
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '配送方法を更新しました。',
        ]);
    }

    public function shipping_method(ShippingMethodUpdateRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $OrderDetailUpdateService = new OrderDetailUpdateService;
                // 受注をロックして取得
                $order = $OrderDetailUpdateService->getOrder($request);
                // 配送方法を更新できるか確認
                $OrderDetailUpdateService->checkUpdatableShippingMethod($order);
                // 配送方法を更新
                $OrderDetailUpdateService->updateShippingMethod($request, $order);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '配送方法を更新しました。',
        ]);
    }

    public function tracking_no(TrackingNoUpdateRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $OrderDetailUpdateService = new OrderDetailUpdateService;
                // 受注をロックして取得
                $order = $OrderDetailUpdateService->getOrder($request);
                // 配送伝票番号を更新できるか確認
                $OrderDetailUpdateService->checkUpdatableTrackingNo($order);
                // 配送伝票番号を更新
                $OrderDetailUpdateService->updateTrackingNo($request, $order);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '配送伝票番号を更新しました。',
        ]);
    }

    public function order_memo(OrderMemoUpdateRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $OrderDetailUpdateService = new OrderDetailUpdateService;
                // 受注をロックして取得
                $order = $OrderDetailUpdateService->getOrder($request);
                // 受注メモを更新できるか確認
                $OrderDetailUpdateService->checkUpdatableOrderMemo($order);
                // 受注メモを更新
                $OrderDetailUpdateService->updateOrderMemo($request, $order);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '受注メモを更新しました。',
        ]);
    }

    public function shipping_work_memo(ShippingWorkMemoUpdateRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $OrderDetailUpdateService = new OrderDetailUpdateService;
                // 受注をロックして取得
                $order = $OrderDetailUpdateService->getOrder($request);
                // 出荷作業メモを更新できるか確認
                $OrderDetailUpdateService->checkUpdatableShippingWorkMemo($order);
                // 出荷作業メモを更新
                $OrderDetailUpdateService->updateShippingWorkMemo($request, $order);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '出荷作業メモを更新しました。',
        ]);
    }

    public function desired_delivery_date(DesiredDeliveryDateUpdateRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $OrderDetailUpdateService = new OrderDetailUpdateService;
                // 受注をロックして取得
                $order = $OrderDetailUpdateService->getOrder($request);
                // 配送希望日を更新できるか確認
                $OrderDetailUpdateService->checkUpdatableDesiredDeliveryDate($order);
                // 配送希望日を更新
                $OrderDetailUpdateService->updateDesiredDeliveryDate($request, $order);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '配送希望日を更新しました。',
        ]);
    }
}