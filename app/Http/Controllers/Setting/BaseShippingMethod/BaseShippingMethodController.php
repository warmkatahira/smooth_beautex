<?php

namespace App\Http\Controllers\Setting\BaseShippingMethod;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Base;

class BaseShippingMethodController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '倉庫別配送方法']);
        // 倉庫を取得
        $bases = Base::getAll()
                    ->with([
                        'base_shipping_methods.shipping_method.delivery_company',
                        'base_shipping_methods.e_hiden_version',
                    ])
                    ->get();
        return view('setting.base_shipping_method.index')->with([
            'bases' => $bases,
        ]);
    }
}