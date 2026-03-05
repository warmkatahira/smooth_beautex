<?php

namespace App\Http\Controllers\Shipping\ShippingMenu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShippingMenuController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '出荷メニュー']);
        return view('shipping.shipping_menu.index')->with([
        ]);
    }
}