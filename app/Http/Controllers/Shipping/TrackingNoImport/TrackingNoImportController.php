<?php

namespace App\Http\Controllers\Shipping\TrackingNoImport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Shipping\TrackingNoImport\TrackingNoImportService;
// その他
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

class TrackingNoImportController extends Controller
{
    public function import(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // 現在の日時を取得
                $nowDate = CarbonImmutable::now();
                // インスタンス化
                $TrackingNoImportService = new TrackingNoImportService;
                // 選択したデータをストレージにインポート
                $save_file_path = $TrackingNoImportService->importData($request->file('select_file'));
                // インポートしたデータのヘッダーを確認
                $result = $TrackingNoImportService->checkHeader($save_file_path);
                // 反映するデータを配列に格納（同時にバリデーションも実施）
                $data = $TrackingNoImportService->setArrayImportData($save_file_path, $result['order_control_id_column'], $result['tracking_no_column']);
                // バリデーションエラー配列の中にnull以外があれば、エラー情報を出力
                if (count(array_filter($data['validation_error'])) != 0) {
                    // セッションにエラー情報を格納
                    session(['tracking_no_upload_error' => array(['エラー情報' => $data['validation_error'], 'アップロード日時' => $nowDate])]);
                    throw new \Exception('データが正しくない為、アップロードできませんでした。');
                }
                // 配送伝票番号反映処理
                $TrackingNoImportService->updateTrackingNo($data['upload_data'], $data['order_control_id_arr']);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '配送伝票番号取込が完了しました。',
        ]);
    }
}