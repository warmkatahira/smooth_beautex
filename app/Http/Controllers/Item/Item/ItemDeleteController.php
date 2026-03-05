<?php

namespace App\Http\Controllers\Item\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Item;
// サービス
use App\Services\Item\Item\ItemDeleteService;
// リクエスト
use App\Http\Requests\Item\Item\ItemDeleteRequest;
// その他
use Illuminate\Support\Facades\DB;

class ItemDeleteController extends Controller
{
    public function delete(ItemDeleteRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $ItemDeleteService = new ItemDeleteService;
                // 商品が削除可能か確認
                $item = $ItemDeleteService->checkDeletable($request);
                // 商品を削除
                $ItemDeleteService->deleteItem($item);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '商品を削除しました。',
        ]);
    }
}