<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
// その他
use Illuminate\Support\Facades\Auth;

class WarmCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 会社IDが「warm」以外の場合はトップページへ遷移
        if(Auth::user()->company_id !== 'warm'){
            return redirect()->route('dashboard.index')->with([
                'alert_type' => 'error',
                'alert_message' => '不正なアクセスです。',
            ]);
        }
        return $next($request);
    }
}
