<?php

namespace App\Http\Controllers\Item\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Item;

class ItemBarcodeController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '商品']);
        // 商品を取得
        $item = Item::getSpecify($request->item_id)->first();
        return view('item.item_barcode.index')->with([
            'item' => $item,
        ]);
    }
}