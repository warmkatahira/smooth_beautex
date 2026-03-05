<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- 在庫メニュー +-+-+-+-+-+-+-+-
use App\Http\Controllers\Stock\StockMenu\StockMenuController;
// +-+-+-+-+-+-+-+- 在庫 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Stock\Stock\StockController;
use App\Http\Controllers\Stock\Stock\StockDownloadController;
// +-+-+-+-+-+-+-+- 入力在庫数操作 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Stock\InputStockOperation\InputStockOperationController;
use App\Http\Controllers\Stock\InputStockOperation\InputStockOperationEnterController;
// +-+-+-+-+-+-+-+- 在庫履歴 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Stock\StockHistory\StockHistoryController;
use App\Http\Controllers\Stock\StockHistory\StockHistoryDownloadController;
// +-+-+-+-+-+-+-+- 入庫検品 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Stock\ReceivingInspection\ReceivingInspectionController;
use App\Http\Controllers\Stock\ReceivingInspection\ReceivingInspectionEnterController;
// +-+-+-+-+-+-+-+- ロケーション更新 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Stock\ItemLocationUpdate\ItemLocationUpdateController;

Route::middleware('common')->group(function (){
    // +-+-+-+-+-+-+-+- 在庫メニュー +-+-+-+-+-+-+-+-
    Route::controller(StockMenuController::class)->prefix('stock_menu')->name('stock_menu.')->group(function(){
        Route::get('', 'index')->name('index');
    });
    // +-+-+-+-+-+-+-+- 在庫 +-+-+-+-+-+-+-+-
    Route::controller(StockController::class)->prefix('stock')->name('stock.')->group(function(){
        Route::get('index_by_item', 'index_by_item')->name('index_by_item');
        Route::get('index_by_stock', 'index_by_stock')->name('index_by_stock');
    });
    Route::controller(StockDownloadController::class)->prefix('stock_download')->name('stock_download.')->group(function(){
        Route::get('download', 'download')->name('download');
    });
    Route::middleware(['warm_check'])->group(function () {
        // +-+-+-+-+-+-+-+- ロケーション更新 +-+-+-+-+-+-+-+-
        Route::controller(ItemLocationUpdateController::class)->prefix('item_location_update')->name('item_location_update.')->group(function(){
            Route::post('update', 'update')->name('update');
        });
        // +-+-+-+-+-+-+-+- 入力在庫数操作 +-+-+-+-+-+-+-+-
        Route::controller(InputStockOperationController::class)->prefix('input_stock_operation')->name('input_stock_operation.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(InputStockOperationEnterController::class)->prefix('input_stock_operation_enter')->name('input_stock_operation_enter.')->group(function(){
            Route::post('enter', 'enter')->name('enter');
        });
        // +-+-+-+-+-+-+-+- 在庫履歴 +-+-+-+-+-+-+-+-
        Route::controller(StockHistoryController::class)->prefix('stock_history')->name('stock_history.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(StockHistoryDownloadController::class)->prefix('stock_history_download')->name('stock_history_download.')->group(function(){
            Route::get('download', 'download')->name('download');
        });
        // +-+-+-+-+-+-+-+- 入庫検品 +-+-+-+-+-+-+-+-
        Route::controller(ReceivingInspectionController::class)->prefix('receiving_inspection')->name('receiving_inspection.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::get('ajax_check_item_id_code', 'ajax_check_item_id_code');
            Route::post('ajax_delete_item_id', 'ajax_delete_item_id');
            Route::post('ajax_get_item_id_change_target', 'ajax_get_item_id_change_target');
            Route::post('ajax_change_item_id', 'ajax_change_item_id');
        });
        Route::controller(ReceivingInspectionEnterController::class)->prefix('receiving_inspection_enter')->name('receiving_inspection_enter.')->group(function(){
            Route::post('enter', 'enter')->name('enter');
        });
    });
});