<?php

namespace App\Http\Controllers\SystemAdmin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'ユーザー']);
        // ユーザーを取得
        $users = User::getAll()->with('role')->with('company')->get();
        return view('system_admin.user.index')->with([
            'users' => $users,
        ]);
    }
}