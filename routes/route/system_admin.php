<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- システム管理 +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\SystemAdminMenu\SystemAdminMenuController;
// +-+-+-+-+-+-+-+- 倉庫 +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\Base\BaseController;
use App\Http\Controllers\SystemAdmin\Base\BaseCreateController;
use App\Http\Controllers\SystemAdmin\Base\BaseUpdateController;
// +-+-+-+-+-+-+-+- ユーザー +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\User\UserController;
use App\Http\Controllers\SystemAdmin\User\UserUpdateController;
// +-+-+-+-+-+-+-+- 操作ログ +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\OperationLog\OperationLogController;
use App\Http\Controllers\SystemAdmin\OperationLog\OperationLogDownloadController;
// +-+-+-+-+-+-+-+- システム資料 +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\SystemDocument\SystemDocumentController;
use App\Http\Controllers\SystemAdmin\SystemDocument\SystemDocumentCreateController;
use App\Http\Controllers\SystemAdmin\SystemDocument\SystemDocumentDeleteController;
// +-+-+-+-+-+-+-+- 請求データ +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\BillingData\BillingDataController;
use App\Http\Controllers\SystemAdmin\BillingData\BillingDataDownloadController;

Route::middleware('common')->group(function (){
    
    Route::middleware(['warm_check'])->group(function () {
        
    });
    
    Route::middleware(['warm_check'])->group(function () {
        // +-+-+-+-+-+-+-+- システム管理メニュー +-+-+-+-+-+-+-+-
        Route::controller(SystemAdminMenuController::class)->prefix('system_admin_menu')->name('system_admin_menu.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        // +-+-+-+-+-+-+-+- システム資料 +-+-+-+-+-+-+-+-
        Route::controller(SystemDocumentController::class)->prefix('system_document')->name('system_document.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        // +-+-+-+-+-+-+-+- 請求データ +-+-+-+-+-+-+-+-
        Route::controller(BillingDataController::class)->prefix('billing_data')->name('billing_data.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(BillingDataDownloadController::class)->prefix('billing_data_download')->name('billing_data_download.')->group(function(){
            Route::get('download', 'download')->name('download');
        });
        Route::middleware(['admin_check'])->group(function () {
            Route::controller(SystemDocumentCreateController::class)->prefix('system_document_create')->name('system_document_create.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::post('create', 'create')->name('create');
            });
            Route::controller(SystemDocumentDeleteController::class)->prefix('system_document_delete')->name('system_document_delete.')->group(function(){
                Route::post('delete', 'delete')->name('delete');
            });
            // +-+-+-+-+-+-+-+- 倉庫 +-+-+-+-+-+-+-+-
            Route::controller(BaseController::class)->prefix('base')->name('base.')->group(function(){
                Route::get('', 'index')->name('index');
            });
            Route::controller(BaseCreateController::class)->prefix('base_create')->name('base_create.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::post('create', 'create')->name('create');
            });
            Route::controller(BaseUpdateController::class)->prefix('base_update')->name('base_update.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::post('update', 'update')->name('update');
            });
            // +-+-+-+-+-+-+-+- ユーザー +-+-+-+-+-+-+-+-
            Route::controller(UserController::class)->prefix('user')->name('user.')->group(function(){
                Route::get('', 'index')->name('index');
            });
            Route::controller(UserUpdateController::class)->prefix('user_update')->name('user_update.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::post('update', 'update')->name('update');
            });
            // +-+-+-+-+-+-+-+- 操作ログ +-+-+-+-+-+-+-+-
            Route::controller(OperationLogController::class)->prefix('operation_log')->name('operation_log.')->group(function(){
                Route::get('', 'index')->name('index');
            });
            Route::controller(OperationLogDownloadController::class)->prefix('operation_log_download')->name('operation_log_download.')->group(function(){
                Route::get('download', 'download')->name('download');
            });
        });
    });
});