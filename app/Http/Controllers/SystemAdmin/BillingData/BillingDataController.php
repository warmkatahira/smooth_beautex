<?php

namespace App\Http\Controllers\SystemAdmin\BillingData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillingDataController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '請求データ']);
        return view('system_admin.billing_data.index');
    }
}