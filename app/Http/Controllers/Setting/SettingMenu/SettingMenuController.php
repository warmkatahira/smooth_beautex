<?php

namespace App\Http\Controllers\Setting\SettingMenu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingMenuController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '設定メニュー']);
        return view('setting.setting_menu.index')->with([
        ]);
    }
}