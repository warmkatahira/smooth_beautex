<?php

namespace App\Http\Controllers\Shipping\Nifuda;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\NifudaCreateHistory;
// その他
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class NifudaDownloadController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '荷札データダウンロード']);
        // 荷札データ作成履歴を取得
        $nifuda_create_histories = NifudaCreateHistory::getAll()->get();
        return view('shipping.nifuda.index')->with([
            'nifuda_create_histories' => $nifuda_create_histories,
        ]);
    }

    public function download(Request $request)
    {
        // 荷札データ発行履歴を取得
        $nifuda_create_history = NifudaCreateHistory::getSpecify($request->nifuda_create_history_id)->first();
        // パスが存在しない場合
        if(!Storage::disk('public')->exists('nifuda/'.$nifuda_create_history->directory_name)){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => '荷札データが存在しません。',
            ]);
        }
        // ZIPファイルの名前
        $zip_file_name = $nifuda_create_history->directory_name.'.zip';
        // ZipArchiveクラスのインスタンス作成
        $zip = new ZipArchive;
        // ZIPファイルの保存パス(storageディレクトリ内)
        $zip_file_path = storage_path('app/'.$zip_file_name);
        // ZIPファイルを作成
        if($zip->open($zip_file_path, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE){
            // 対象ディレクトリ内のファイルを取得
            $files = Storage::disk('public')->files('nifuda/' . $nifuda_create_history->directory_name);
            // 各ファイルをZIPに追加
            foreach($files as $file){
                // フルパスを取得
                $full_path = Storage::disk('public')->path($file);
                // 日本語ファイル名の場合、Shift-JISに変換してZIPに追加
                $zip->addFile($full_path, mb_convert_encoding(basename($file), 'SJIS-win', 'UTF-8'));
            }
            // ZIPファイルを閉じる
            $zip->close();
        }
        // 作成したZIPファイルをダウンロード(ダウンロード後に削除している)
        return response()->download($zip_file_path)->deleteFileAfterSend(true);
    }
}
