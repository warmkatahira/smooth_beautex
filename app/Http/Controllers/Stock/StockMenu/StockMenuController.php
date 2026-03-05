<?php

namespace App\Http\Controllers\Stock\StockMenu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockMenuController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '在庫メニュー']);
        return view('stock.stock_menu.index')->with([
        ]);
    }
}