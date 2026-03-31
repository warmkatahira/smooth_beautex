<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
// ミドルウェア
use Illuminate\Auth\Middleware\Authenticate;
use App\Http\Middleware\CheckUserStatusMiddleware;
use App\Http\Middleware\AdminCheckMiddleware;
use App\Http\Middleware\OperationLogRecordMiddleware;
use App\Http\Middleware\WarmCheckMiddleware;
use App\Http\Middleware\NavigationRouteCheckMiddleware;
use App\Http\Middleware\ForcePasswordChangeMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware){
        // 共通(グループミドルウェア)
        $middleware->appendToGroup('common', [
            // 認証確認
            Authenticate::class,
            // パスワード変更確認
            ForcePasswordChangeMiddleware::class,
            // 操作ログ記録
            OperationLogRecordMiddleware::class,
            // ユーザーステータス確認
            CheckUserStatusMiddleware::class,
            // ナビゲーションボタンのクラス確認用
            NavigationRouteCheckMiddleware::class,
        ]);
        // ルートミドルウェア
        $middleware->alias([
            // 権限「admin」チェック
            'admin_check' => AdminCheckMiddleware::class,
            // 会社「warm」チェック
            'warm_check' => WarmCheckMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions){
        //
    })->create();
