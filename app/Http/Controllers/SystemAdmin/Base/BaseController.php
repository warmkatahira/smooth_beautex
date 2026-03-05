<?php

namespace App\Http\Controllers\SystemAdmin\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Base;

class BaseController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '倉庫']);
        // 倉庫を取得
        $bases = Base::getAll()->get();
        return view('system_admin.base.index')->with([
            'bases' => $bases,
        ]);
    }
}