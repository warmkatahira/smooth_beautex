<?php

namespace App\Http\Controllers\SystemAdmin\SystemAdminMenu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SystemAdminMenuController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'システム管理メニュー']);
        return view('system_admin.system_admin_menu.index')->with([
        ]);
    }
}