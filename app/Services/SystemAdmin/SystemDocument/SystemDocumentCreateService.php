<?php

namespace App\Services\SystemAdmin\SystemDocument;

// モデル
use App\Models\SystemDocument;
// その他
use Illuminate\Support\Facades\Storage;

class SystemDocumentCreateService
{
    // システム資料を追加
    public function createSystemDocument($request)
    {
        // ファイルを取得
        $file = $request->file('select_file');
        // 選択したデータのファイル名を取得
        $file_name = $file->getClientOriginalName();
        // 既に同じ名前のファイル名が存在している場合
        if(Storage::disk('public')->exists('system_document/'.$file_name)){
            throw new \RuntimeException('既に同じ名前のファイル名が存在しています。');
        }
        // 保存するパスを設定
        $file_path = storage_path('app/public/system_document');
        // ファイルを保存
        $file->move($file_path, $file_name);
        // レコードを追加
        SystemDocument::create([
            'file_name' => $file_name,
            'sort_order' => $request->sort_order,
            'is_internal' => $request->is_internal,
        ]);
    }
}