<?php

namespace App\Http\Controllers\Order\OrderImport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\OrderImportHistory;
// サービス
use App\Services\Order\OrderImport\OrderImportService;
use App\Services\Order\OrderImport\OrderImportForQoo10Service;
use App\Services\Common\ImportErrorCreateService;
use App\Services\Order\OrderAllocate\OrderAllocateService;
use App\Services\Order\OrderImport\AutoProcessApplyService;
// 例外
use App\Exceptions\OrderImportException;
// その他
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class OrderImportController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '受注取込']);
        $order_import_histories = OrderImportHistory::getDispData()->get();
        return view('order.order_import.index')->with([
            'order_import_histories' => $order_import_histories,
        ]);
    }

    public function import(Request $request)
    {
        // インスタンス化
        $OrderImportService = new OrderImportService;
        $ImportErrorCreateService = new ImportErrorCreateService;
        $OrderAllocateService = new OrderAllocateService;
        $AutoProcessApplyService = new AutoProcessApplyService;
        // 変数を初期化
        $import_info = null;
        $order_no_num = null;
        $error_file_name = null;
        try {
            $result = DB::transaction(function () use ($request, $OrderImportService, $ImportErrorCreateService, $AutoProcessApplyService, &$import_info, &$order_no_num, &$error_file_name){
                // インスタンス化
                $OrderImportForQoo10Service = new OrderImportForQoo10Service;
                // 変数を初期化
                $message = null;
                // 現在の日時を取得
                $nowDate = CarbonImmutable::now();
                // 選択したデータをストレージにインポート
                $import_info = $OrderImportService->importData($request->file('select_file'));
                // インポートした受注区分を取得
                $order_category_id = $OrderImportService->getOrderCategoryId($import_info['save_file_path']);
                // インポートしたデータのヘッダーを確認
                $OrderImportService->checkHeader($import_info['save_file_path'], $nowDate, $import_info, $order_category_id);
                // 追加する受注データを配列に格納（同時にバリデーションも実施）
                $order = $OrderImportForQoo10Service->setArrayImport($import_info['save_file_path'], $nowDate, $order_category_id);
                // バリデーションエラー配列の中にnull以外があれば、エラー情報を出力
                if(count(array_filter($order['validation_error'])) != 0){
                    // インポートエラー情報のファイルを作成
                    $error_file_name = $ImportErrorCreateService->createImportError('受注取込エラー', $order['validation_error'], $nowDate, 'データ不正', null);
                    throw new OrderImportException("データが正しくない為、取り込みできませんでした。", $import_info, null, $error_file_name);
                }
                // order_importsへデータを追加
                $OrderImportService->createArrayImportData($order['create_data']);
                // 既に取込済みの受注を削除して、削除後の注文番号数を返す
                $order_no_num = $OrderImportService->deleteImportedOrder();
                // インポートできない受注があればエラーファイルを作成
                if(!empty($order_no_num['import_already'])){
                    // メッセージを格納
                    $message = '既に取り込み済みの受注がありました。';
                    // インポートエラー情報のファイルを作成
                    $error_file_name = $ImportErrorCreateService->createImportError('受注取込エラー', $order_no_num['import_already'], $nowDate, '新規取込なし', 'order_no');
                }
                // order_importsテーブルにレコードが残っていれば処理を継続
                if($order_no_num['after_order_no_num'] == 0){
                    throw new OrderImportException("新たに取り込みできる受注がありませんでした。", $import_info, $order_no_num, $error_file_name);
                }
                // 出荷倉庫IDを更新
                $OrderImportService->updateShippingBaseId();
                // 受注管理IDを採番
                $OrderImportService->updateOrderControlId();
                // order_item_codeを更新
                $OrderImportForQoo10Service->updateOrderItemCode();
                // 購入数を更新
                $OrderImportForQoo10Service->updateOrderQuantity();
                // ordersとorder_itemsテーブルへ追加
                $OrderImportService->createOrder();
                // order_import_historiesテーブルへ追加
                $OrderImportService->createOrderImportHistory($import_info, $order_no_num, $error_file_name, $message);
                // 自動処理を適用
                $AutoProcessApplyService->apply();
                return $order_no_num;
            });
        } catch (OrderImportException $e){
            // 渡された内容を取得
            $import_info = $e->getImportInfo();
            $order_no_num = $e->getOrderNoNum();
            $error_file_name = $e->getErrorFileName();
            // order_import_historiesテーブルへ追加
            $OrderImportService->createOrderImportHistory($import_info, $order_no_num, $error_file_name, $e->getMessage());
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        // 引当処理
        $OrderAllocateService->procOrderAllocate(null);
        // 表示するメッセージを作成
        $alert = $OrderImportService->createDispMessage($result);
        return redirect()->back()->with([
            'alert_type' => $alert['type'],
            'alert_message' => $alert['message'],
        ]);
    }

    public function error_download(Request $request)
    {
        // ファイル名とフルパスを変数に格納
        $filename = $request->filename;
        $path = storage_path('app/public/export/order_import_error/'.$filename);
        // ファイルが存在しない場合はエラーを返す
        if(!file_exists($path)){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => 'ファイルが存在しません。',
            ]);
        }
        // ダウンロード処理
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=' . $filename,
        ];
        return response()->download($path, $filename, $headers);
    }
}
