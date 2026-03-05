<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
// その他
use Illuminate\Support\Facades\Auth;

class CheckUserStatusMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ステータスが1以外のユーザーはログインさせない
        if(Auth::user()->status != 1){
            session()->flash('alert_type', "error");
            session()->flash('alert_message', "使用できないユーザーです。");
            // ログアウトさせる
            Auth::logout();
            // welcomeページへ遷移
            return redirect()->route('welcome.index');
        }
        return $next($request);
    }
}
