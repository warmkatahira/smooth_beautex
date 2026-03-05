<?php

namespace App\Http\Controllers\Order\HikiatemachiList;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Order\HikiatemachiList\HikiatemachiListCreateService;

class HikiatemachiListController extends Controller
{
    public function create()
    {
        try{
            // インスタンス化
            $HikiatemachiListCreateService = new HikiatemachiListCreateService;
            // 出力内容を取得
            $orders = $HikiatemachiListCreateService->getCreateItem();
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return view('order.document.hikiatemachi_list')->with([
            'orders' => $orders,
        ]);
    }
}