<?php

namespace App\Http\Controllers\Setting\Shipper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Shipper;

class ShipperController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '荷送人']);
        // 荷送人を取得
        $shippers = Shipper::getAll()->get();
        return view('setting.shipper.index')->with([
            'shippers' => $shippers,
        ]);
    }
}