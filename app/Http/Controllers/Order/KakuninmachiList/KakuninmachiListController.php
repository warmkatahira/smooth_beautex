<?php

namespace App\Http\Controllers\Order\KakuninmachiList;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Order\KakuninmachiList\KakuninmachiListCreateService;

class KakuninmachiListController extends Controller
{
    public function create()
    {
        try{
            // インスタンス化
            $KakuninmachiListCreateService = new KakuninmachiListCreateService;
            // 出力内容を取得
            $orders = $KakuninmachiListCreateService->getCreateItem();
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return view('order.document.kakuninmachi_list')->with([
            'orders' => $orders,
        ]);
    }
}