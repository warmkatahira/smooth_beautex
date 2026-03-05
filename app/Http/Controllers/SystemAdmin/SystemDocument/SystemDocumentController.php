<?php

namespace App\Http\Controllers\SystemAdmin\SystemDocument;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\SystemDocument;

class SystemDocumentController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'システム資料']);
        // 全てのレコードを取得
        $system_documents = SystemDocument::getAll()->get();
        return view('system_admin.system_document.index')->with([
            'system_documents' => $system_documents,
        ]);
    }
}