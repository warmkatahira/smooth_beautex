<?php

namespace App\Http\Controllers\Item\ItemUpload;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\ItemUploadHistory;
// サービス
use App\Services\Item\ItemUpload\ItemUploadService;
// ジョブ
use App\Jobs\ItemUploadJobs;
// 列挙
use App\Enums\ItemUploadEnum;
// その他
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ItemUploadController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '商品アップロード']);
        // 商品アップロード履歴を取得
        $item_upload_histories = ItemUploadHistory::getAll()->get();
        return view('item.item_upload.index')->with([
            'item_upload_histories' => $item_upload_histories,
        ]);
    }

    public function upload(Request $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $ItemUploadService = new ItemUploadService;
                // 選択したデータをストレージにインポート
                $import = $ItemUploadService->importData($request->file('select_file'));
                // インポートしたデータのヘッダーを確認
                $result = $ItemUploadService->checkHeader($import['save_file_full_path'], $request->upload_type);
                // エラーがあれば処理を中断
                if(!is_null($result)){
                    throw new \Exception($result);
                }
                // user_noを取得
                $user_no = Auth::user()->user_no;
                // ジョブを溜める
                ItemUploadJobs::dispatch($user_no, $import['save_file_full_path'], $import['upload_original_file_name'], $request->upload_type)->onQueue('item_upload');
                
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '商品をアップロードしました。<br>処理が完了するまでお待ちください。',
        ]);
    }

    public function error_download(Request $request)
    {
        // ファイル名とフルパスを変数に格納
        $filename = $request->filename;
        $path = storage_path('app/public/export/item_upload_error/'.$filename);
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