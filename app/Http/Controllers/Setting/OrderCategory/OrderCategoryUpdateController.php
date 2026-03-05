<?php

namespace App\Http\Controllers\Setting\OrderCategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\OrderCategory;
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
        // 荷送人を取得
        $shippers = Shipper::getAll()->get();
        return view('setting.order_category.update')->with([
            'order_category' => $order_category,
            'shippers' => $shippers,
        ]);
    }

    public function update(OrderCategoryUpdateRequest $request)
    {
        /* try{
            DB::transaction(function () use ($request){ */
                // インスタンス化
                $OrderCategoryUpdateService = new OrderCategoryUpdateService;
                // 受注区分を更新
                $order_category = $OrderCategoryUpdateService->updateOrderCategory($request);
                // 受注区分画像を削除
                $OrderCategoryUpdateService->deleteOrderCategoryImage($request, $order_category);
                // 受注区分画像を保存
                $OrderCategoryUpdateService->saveOrderCategoryImage($request, $order_category);
           /*  });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => '受注区分の更新に失敗しました。',
            ]);
        } */
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '受注区分を更新しました。',
        ]);
    }
}