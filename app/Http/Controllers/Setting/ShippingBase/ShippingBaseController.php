<?php

namespace App\Http\Controllers\Setting\ShippingBase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Prefecture;
use App\Models\Base;
// その他
use Illuminate\Support\Facades\DB;

class ShippingBaseController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '出荷倉庫']);
        // 倉庫にいくつの都道府県が紐付いているか取得
        $prefecture_by_base = Prefecture::join('bases', 'bases.base_id', 'prefectures.shipping_base_id')
                                ->select('shipping_base_id', DB::raw("COUNT(DISTINCT prefecture_id) as setting_count"))
                                ->groupBy('shipping_base_id')
                                ->orderBy('bases.sort_order', 'asc')
                                ->get();
        // 都道府県を取得
        $prefectures = Prefecture::getAll()->with('base')->get();
        // 倉庫を取得
        $bases = Base::getAll()->get();
        return view('setting.shipping_base.index')->with([
            'prefectures' => $prefectures,
            'bases' => $bases,
            'prefecture_by_base' => $prefecture_by_base,
        ]);
    }
}