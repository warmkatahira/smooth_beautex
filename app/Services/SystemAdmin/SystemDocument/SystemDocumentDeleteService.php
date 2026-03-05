<?php

namespace App\Services\SystemAdmin\SystemDocument;

// モデル
use App\Models\SystemDocument;
// その他
use Illuminate\Support\Facades\Storage;

class SystemDocumentDeleteService
{
    // システム資料を削除
    public function deleteSystemDocument($system_document_id)
    {
        // 対象のシステム資料を取得
        $system_document = SystemDocument::getSpecify($system_document_id)->first();
        // ファイルパスを取得
        $file_path = storage_path('app/public/system_document/' . $system_document->file_name);
        // ファイルが存在する場合
        if(file_exists($file_path)){
            // ファイルを削除
            unlink($file_path);
        }
        // レコードを削除
        $system_document->delete();
        return;
    }
}