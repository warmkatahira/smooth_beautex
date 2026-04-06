<?php

namespace App\Http\Controllers\Shipping\ShippingWorkEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Order;
use App\Models\Base;
// サービス
use App\Services\Shipping\ShippingWorkEnd\ShippingWorkEndService;
use App\Services\Common\StockHistoryCreateService;
use App\Services\Common\MieruService;
// 列挙
use App\Enums\StockHistoryCategoryEnum;
use App\Enums\OrderStatusEnum;
// その他
use Illuminate\Support\Facades\DB;
// 例外
use App\Exceptions\ShippingWorkEndException;

class ShippingWorkEndController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '出荷完了']);
        // 出荷完了対象と出荷完了対象外を出荷倉庫・出荷予定日毎で取得
        $shipping_work_end_info = Order::where('order_status_id', OrderStatusEnum::SAGYO_CHU)
                                    ->selectRaw('shipping_groups.shipping_base_id, COUNT(*) as count')
                                    ->selectRaw('DATE(shipping_groups.estimated_shipping_date) as estimated_shipping_date')
                                    ->selectRaw('CASE WHEN is_shipping_inspection_complete = 1 OR is_shipping_inspection_skipped = 1 THEN 1 ELSE 0 END as is_target')
                                    ->leftJoin('shipping_groups', 'shipping_groups.shipping_group_id', '=', 'orders.shipping_group_id')
                                    ->groupBy('shipping_groups.shipping_base_id', 'is_target', 'estimated_shipping_date')
                                    ->with('base')
                                    ->get();
        // 配列を初期化
        $shipping_work_end_info_arr = [];
        // 倉庫を取得
        $bases = Base::getAll()->get();
        // レコードの分だけループ処理
        foreach($shipping_work_end_info as $row){
            $base_name = $row->base->base_name;
            $estimated_shipping_date = $row->estimated_shipping_date ?? '未設定';
            $is_target = $row->is_target;
            if(!isset($shipping_work_end_info_arr[$base_name][$estimated_shipping_date])){
                $shipping_work_end_info_arr[$base_name][$estimated_shipping_date] = [0 => 0, 1 => 0];
            }
            $shipping_work_end_info_arr[$base_name][$estimated_shipping_date][$is_target] = $row->count;
        }
        return view('shipping.shipping_work_end.index')->with([
            'shipping_work_end_info_arr' => $shipping_work_end_info_arr,
        ]);
    }

    public function enter(Request $request)
    {
        // インスタンス化
        $ShippingWorkEndService = new ShippingWorkEndService;
        $MieruService = new MieruService;
        // ミエルの進捗を更新する対象を取得
        $MieruService->getUpdateProgressTarget(null);
        try {
            DB::transaction(function () use($request, $ShippingWorkEndService) {
                // インスタンス化
                $StockHistoryCreateService = new StockHistoryCreateService;
                // 出荷完了対象を取得
                $orders = $ShippingWorkEndService->getShippingWorkEndTarget();
                // 出荷完了対象が正常に完了処理できるか確認
                $order_control_ids = $ShippingWorkEndService->isShippingWorkEndAvailable($orders);
                // stocksテーブル更新処理
                $stocks = $ShippingWorkEndService->updateStock($order_control_ids);
                // ordersテーブル更新処理
                $ShippingWorkEndService->updateOrder($order_control_ids);
                // 出荷グループを削除
                $ShippingWorkEndService->deleteShippingGroup();
                // 出荷完了履歴に追加
                $ShippingWorkEndService->createShippingWorkEndHistory($order_control_ids->count(), 1, null, null);
                // 在庫履歴に追加
                $error = $StockHistoryCreateService->createStcokHistory(StockHistoryCategoryEnum::SHUKKA, null, $stocks);
            });
        } catch (ShippingWorkEndException $e) {
            // 渡された内容を取得
            $target_count = $e->getTargetCount();
            $is_successful = $e->getIsSuccessful();
            $error_file_name = $e->getErrorFileName();
            // 出荷完了履歴に追加
            $ShippingWorkEndService->createShippingWorkEndHistory($target_count, $is_successful, $e->getMessage(), $error_file_name);
            return redirect()->route('shipping_work_end_history.index')->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        // 出荷確定をミエルに送信
        $MieruService->updateShippingConfirmed();
        return redirect()->route('shipping_work_end_history.index')->with([
            'alert_type' => 'success',
            'alert_message' => '出荷完了が完了しました。',
        ]);
    }
}