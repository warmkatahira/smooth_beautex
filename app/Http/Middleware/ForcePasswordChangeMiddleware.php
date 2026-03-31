<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
// その他
use Illuminate\Support\Facades\Auth;

class ForcePasswordChangeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ログインしているかつ must_change_passwordが1の場合
        if(auth()->check() && auth()->user()->must_change_password){
            // すでにパスワード変更画面にいる場合は許可
            if($request->routeIs('password.change.*')){
                return $next($request);
            }
            // ログアウト
            Auth::logout();
            return redirect()->route('password.change.form');
        }
        return $next($request);
    }
}