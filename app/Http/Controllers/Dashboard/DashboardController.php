<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Dashboard\InfoGetService;
use App\Services\Dashboard\ChartService;

class DashboardController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'ダッシュボード']);
        // インスタンス化
        $InfoGetService = new InfoGetService;
        $ChartService = new ChartService;
        // グラフ表示する期間を取得
        $date = $ChartService->getPeriod();
        // 期間内の日付を取得
        $dates = $ChartService->getDates($date['from'], $date['to']);
        // 期間内の日別の出荷件数を取得
        $shipping_count = $ChartService->getShippingCount($date['from'], $date['to']);
        // 期間内の日別の出荷数量を取得
        $shipping_quantity = $ChartService->getShippingQuantity($date['from'], $date['to']);
        // 表示する情報を取得
        $info = $InfoGetService->getInfo();
        return view('dashboard')->with([
            'dates' => $dates,
            'shipping_count' => $shipping_count,
            'shipping_quantity' => $shipping_quantity,
            'info' => $info,
        ]);
    }

    public function ajax_get_chart_data()
    {
        // インスタンス化
        $ChartService = new ChartService;
        // グラフ表示する期間を取得
        $date = $ChartService->getPeriod();
        // 期間内の日付を取得
        $dates = $ChartService->getDates($date['from'], $date['to']);
        // 期間内の日別の出荷件数を取得
        $shipping_count = $ChartService->getShippingCount($date['from'], $date['to']);
        // 期間内の日別の出荷数量を取得
        $shipping_quantity = $ChartService->getShippingQuantity($date['from'], $date['to']);
        return response()->json([
            'dates' => $dates,
            'shipping_count' => $shipping_count,
            'shipping_quantity' => $shipping_quantity,
        ]);
    }
}