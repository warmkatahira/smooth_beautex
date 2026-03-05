<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
// その他
use Illuminate\Support\Facades\Auth;

class AdminCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 権限が「admin」以外の場合はトップページへ遷移
        if(Auth::user()->role_id !== 'admin'){
            return redirect()->route('dashboard.index')->with([
                'alert_type' => 'error',
                'alert_message' => 'アクセスできません。',
            ]);
        }
        return $next($request);
    }
}
