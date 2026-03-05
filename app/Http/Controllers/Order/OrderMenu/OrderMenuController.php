<?php

namespace App\Http\Controllers\Order\OrderMenu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderMenuController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '受注メニュー']);
        return view('order.order_menu.index')->with([
        ]);
    }
}