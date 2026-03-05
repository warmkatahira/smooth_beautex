<?php

namespace App\Http\Controllers\Shipping\Nifuda;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Shipping\Nifuda\NifudaCreateService;
// その他
use Illuminate\Support\Facades\DB;

class NifudaCreateController extends Controller
{
    public function create(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // インスタンス化
                $NifudaCreateService = new NifudaCreateService;
                // 作成対象を取得
                $orders = $NifudaCreateService->getCreateOrder($request->shipping_method_id);
                // 荷札データを作成
                $directory_name = $NifudaCreateService->createNifuda($request->shipping_method_id, $orders);
                // 荷札データ作成履歴を追加
                $NifudaCreateService->createNifudaCreateHistory($request->shipping_method_id, $directory_name);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '荷札データを作成しました。',
        ]);
    }
}
