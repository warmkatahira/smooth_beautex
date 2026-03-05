<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- 商品メニュー +-+-+-+-+-+-+-+-
use App\Http\Controllers\Item\ItemMenu\ItemMenuController;
// +-+-+-+-+-+-+-+- 商品 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Item\Item\ItemController;
use App\Http\Controllers\Item\Item\ItemUpdateController;
use App\Http\Controllers\Item\Item\ItemDeleteController;
use App\Http\Controllers\Item\Item\ItemDownloadController;
// +-+-+-+-+-+-+-+- 商品アップロード +-+-+-+-+-+-+-+-
use App\Http\Controllers\Item\ItemUpload\ItemUploadController;
// +-+-+-+-+-+-+-+- 商品QR解析 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Item\ItemQrAnalysis\ItemQrAnalysisController;

Route::middleware('common')->group(function (){
    // +-+-+-+-+-+-+-+- 商品メニュー +-+-+-+-+-+-+-+-
    Route::controller(ItemMenuController::class)->prefix('item_menu')->name('item_menu.')->group(function(){
        Route::get('', 'index')->name('index');
    });
    // +-+-+-+-+-+-+-+- 商品 +-+-+-+-+-+-+-+-
    Route::controller(ItemController::class)->prefix('item')->name('item.')->group(function(){
        Route::get('', 'index')->name('index');
    });
    Route::middleware(['warm_check'])->group(function () {
        Route::controller(ItemUpdateController::class)->prefix('item_update')->name('item_update.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('update', 'update')->name('update');
        });
        Route::controller(ItemDeleteController::class)->prefix('item_delete')->name('item_delete.')->group(function(){
            Route::post('delete', 'delete')->name('delete');
        });
    });
    Route::controller(ItemDownloadController::class)->prefix('item_download')->name('item_download.')->group(function(){
        Route::get('download', 'download')->name('download');
    });
    Route::middleware(['warm_check'])->group(function () {
        // +-+-+-+-+-+-+-+- 商品アップロード +-+-+-+-+-+-+-+-
        Route::controller(ItemUploadController::class)->prefix('item_upload')->name('item_upload.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('upload', 'upload')->name('upload');
            Route::get('error_download', 'error_download')->name('error_download');
        });
        // +-+-+-+-+-+-+-+- 商品QR解析 +-+-+-+-+-+-+-+-
        Route::controller(ItemQrAnalysisController::class)->prefix('item_qr_analysis')->name('item_qr_analysis.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('analysis', 'analysis')->name('analysis');
        });
    });
});