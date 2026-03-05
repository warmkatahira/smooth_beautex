<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Dashboard\InfoGetService;
use App\Services\Dashboard\ChartService;
// その他
use Carbon\CarbonImmutable;

class DashboardController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'ダッシュボード']);
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now()->startOfMonth();
        // 今月、前月、翌月を取得
        $current_month = CarbonImmutable::parse($nowDate)->format('Y-m');
        $previous_month = CarbonImmutable::parse($nowDate)->subMonth()->format('Y-m');
        $next_month = CarbonImmutable::parse($nowDate)->addMonth()->format('Y-m');
        return view('dashboard')->with([
            'current_month' => $current_month,
            'previous_month' => $previous_month,
            'next_month' => $next_month,
        ]);
    }

    public function ajax_get_chart_data(Request $request)
    {
        // インスタンス化
        $ChartService = new ChartService;
        $InfoGetService = new InfoGetService;
        // グラフ表示する期間を取得
        $date = $ChartService->getPeriod($request->month);
        // 期間内の日付を取得
        $dates = $ChartService->getDates($date['from'], $date['to']);
        // 期間内の日別の出荷件数を取得
        $shipping_count = $ChartService->getShippingCount($date['from'], $date['to']);
        // 期間内の日別の出荷数量を取得
        $shipping_quantity = $ChartService->getShippingQuantity($date['from'], $date['to']);
        // 表示する情報を取得
        $info = $InfoGetService->getInfo($date['from'], $date['to']);
        return response()->json([
            'dates' => $dates,
            'shipping_count' => $shipping_count,
            'shipping_quantity' => $shipping_quantity,
            'info' => $info,
        ]);
    }
}