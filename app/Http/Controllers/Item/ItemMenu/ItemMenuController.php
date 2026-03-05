<?php

namespace App\Http\Controllers\Item\ItemMenu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ItemMenuController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '商品メニュー']);
        return view('item.item_menu.index')->with([
        ]);
    }
}