<?php

namespace App\Http\Controllers\Welcome;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// その他
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index()
    {
        // ログインされている場合
        if(Auth::check()){
            return redirect()->route('dashboard.index');
        }
        // ログインされていない場合
        if(!Auth::check()){
            return view('welcome');
        }
    }
}