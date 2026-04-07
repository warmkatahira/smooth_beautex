<?php

namespace App\Http\Controllers\Setting\OrderCategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\OrderCategory;
use App\Models\Mall;
use App\Models\Shipper;
// サービス
use App\Services\Setting\OrderCategory\OrderCategoryUpdateService;
// リクエスト
use App\Http\Requests\Setting\OrderCategory\OrderCategoryUpdateRequest;
// その他
use Illuminate\Support\Facades\DB;

class OrderCategoryUpdateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '受注区分更新']);
        // 受注区分を取得
        $order_category = OrderCategory::getSpecify($request->order_category_id)->first();
        // モールを取得
        $malls = Mall::getAll()->get();
        // 荷送人を取得
        $shippers = Shipper::getAll()->get();
        return view('setting.order_category.update')->with([
            'order_category' => $order_category,
            'malls' => $malls,
            'shippers' => $shippers,
        ]);
    }

    public function update(OrderCategoryUpdateRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $OrderCategoryUpdateService = new OrderCategoryUpdateService;
                // 受注区分を更新
                $OrderCategoryUpdateService->updateOrderCategory($request);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => '受注区分の更新に失敗しました。',
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '受注区分を更新しました。',
        ]);
    }
}