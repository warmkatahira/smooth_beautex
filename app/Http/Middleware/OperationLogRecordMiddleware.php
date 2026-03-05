<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
// その他
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
// 列挙
use App\Enums\OperationLogEnum;

class OperationLogRecordMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // パスが除外リストに含まれていない場合のみログを記録
        if(!in_array($request->path(), OperationLogEnum::NO_OPERATION_RECORD_PATH)){
            // 出力するログを文字列に変換
            $logData = sprintf(
                "User No: %s, User Name: %s, IP Address: %s, Method: %s, Path: %s, Params: %s",
                Auth::user()->user_no,
                Auth::user()->last_name . ' ' . Auth::user()->first_name,
                $request->ip(),
                $request->method(),
                $request->path(),
                json_encode($request->all())
            );
            // ログを出力
            Log::channel('operation')->info($logData);
        }
        return $next($request);
    }
}
