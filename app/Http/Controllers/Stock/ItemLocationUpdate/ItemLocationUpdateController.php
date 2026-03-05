<?php

namespace App\Http\Controllers\Stock\ItemLocationUpdate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Stock\ItemLocationUpdate\ItemLocationUpdateService;
// その他
use Illuminate\Support\Facades\DB;

class ItemLocationUpdateController extends Controller
{
    public function update(Request $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $ItemLocationUpdateService = new ItemLocationUpdateService;
                // 選択したデータをストレージにインポート
                $save_file_path = $ItemLocationUpdateService->importData($request->file('select_file'));
                // インポートしたデータのヘッダーを確認
                $ItemLocationUpdateService->checkHeader($save_file_path);
                // 追加する受注データを配列に格納（同時にバリデーションも実施）
                $update_data = $ItemLocationUpdateService->setArrayImport($save_file_path);
                // 商品ロケーションを更新
                $ItemLocationUpdateService->updateItemLocation($update_data);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '商品ロケーションを更新しました。',
        ]);
    }
}