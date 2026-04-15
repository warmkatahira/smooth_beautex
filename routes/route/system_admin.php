<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- 倉庫 +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\Base\BaseController;
use App\Http\Controllers\SystemAdmin\Base\BaseCreateController;
use App\Http\Controllers\SystemAdmin\Base\BaseUpdateController;
// +-+-+-+-+-+-+-+- ユーザー +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\User\UserController;
use App\Http\Controllers\SystemAdmin\User\UserCreateController;
use App\Http\Controllers\SystemAdmin\User\UserUpdateController;
use App\Http\Controllers\SystemAdmin\User\PasswordResetController;
// +-+-+-+-+-+-+-+- 操作ログ +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\OperationLog\OperationLogController;
use App\Http\Controllers\SystemAdmin\OperationLog\OperationLogDownloadController;
// +-+-+-+-+-+-+-+- 祝日 +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\Holiday\HolidayController;
use App\Http\Controllers\SystemAdmin\Holiday\NationalHolidayController;

Route::middleware('common')->group(function (){
    Route::middleware(['admin_check'])->group(function () {
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
        Route::controller(UserCreateController::class)->prefix('user_create')->name('user_create.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('create', 'create')->name('create');
        });
        Route::controller(UserUpdateController::class)->prefix('user_update')->name('user_update.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('update', 'update')->name('update');
        });
        Route::controller(PasswordResetController::class)->prefix('password_reset')->name('password_reset.')->group(function(){
            Route::post('reset', 'reset')->name('reset');
        });
        // +-+-+-+-+-+-+-+- 操作ログ +-+-+-+-+-+-+-+-
        Route::controller(OperationLogController::class)->prefix('operation_log')->name('operation_log.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(OperationLogDownloadController::class)->prefix('operation_log_download')->name('operation_log_download.')->group(function(){
            Route::get('download', 'download')->name('download');
        });
        // +-+-+-+-+-+-+-+- 祝日 +-+-+-+-+-+-+-+-
        Route::controller(HolidayController::class)->prefix('holiday')->name('holiday.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(NationalHolidayController::class)->prefix('national_holiday')->name('national_holiday.')->group(function(){
            Route::post('get_api', 'get_api')->name('get_api');
        });
    });
});