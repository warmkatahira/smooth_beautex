<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- 受注取込 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\OrderImport\OrderImportController;
// +-+-+-+-+-+-+-+- 受注管理 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\OrderMgt\OrderMgtController;
// +-+-+-+-+-+-+-+- 受注詳細 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\OrderDetail\OrderDetailController;
use App\Http\Controllers\Order\OrderDetail\OrderDetailUpdateController;
// +-+-+-+-+-+-+-+- 受注商品追加 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\OrderItem\OrderItemCreateController;
// +-+-+-+-+-+-+-+- 受注商品削除 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\OrderItem\OrderItemDeleteController;
// +-+-+-+-+-+-+-+- 受注商品削除 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\OrderItem\PastOrderItemController;
// +-+-+-+-+-+-+-+- 受注削除 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\OrderDelete\OrderDeleteController;
// +-+-+-+-+-+-+-+- 出荷作業開始 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\ShippingWorkStart\ShippingWorkStartController;
// +-+-+-+-+-+-+-+- 引当残データダウンロード +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\HikiatezanDownload\HikiatezanDownloadController;

Route::middleware('common')->group(function (){
    Route::middleware(['warm_check'])->group(function () {
        // +-+-+-+-+-+-+-+- 受注取込 +-+-+-+-+-+-+-+-
        Route::controller(OrderImportController::class)->prefix('order_import')->name('order_import.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('import', 'import')->name('import');
            Route::get('error_download', 'error_download')->name('error_download');
        });
    });
    // +-+-+-+-+-+-+-+- 受注管理 +-+-+-+-+-+-+-+-
    Route::controller(OrderMgtController::class)->prefix('order_mgt')->name('order_mgt.')->group(function(){
        Route::get('', 'index')->name('index');
        Route::get('allocate', 'allocate')->name('allocate');
    });
    // +-+-+-+-+-+-+-+- 受注詳細 +-+-+-+-+-+-+-+-
    Route::controller(OrderDetailController::class)->prefix('order_detail')->name('order_detail.')->group(function(){
        Route::get('', 'index')->name('index');
    });
    Route::middleware(['warm_check'])->group(function () {
        Route::controller(OrderDetailUpdateController::class)->prefix('order_detail_update')->name('order_detail_update.')->group(function(){
            Route::post('shipping_base', 'shipping_base')->name('shipping_base');
            Route::post('shipping_method', 'shipping_method')->name('shipping_method');
            Route::post('tracking_no', 'tracking_no')->name('tracking_no');
            Route::post('order_mark', 'order_mark')->name('order_mark');
            Route::post('order_memo', 'order_memo')->name('order_memo');
            Route::post('shipping_work_memo', 'shipping_work_memo')->name('shipping_work_memo');
            Route::post('ship_address', 'ship_address')->name('ship_address');
            Route::post('supplement', 'supplement')->name('supplement');
            Route::post('desired_delivery_date', 'desired_delivery_date')->name('desired_delivery_date');
        });
        // +-+-+-+-+-+-+-+- 受注商品追加 +-+-+-+-+-+-+-+-
        Route::controller(OrderItemCreateController::class)->prefix('order_item_create')->name('order_item_create.')->group(function(){
            Route::get('search', 'search')->name('search');
            Route::post('create', 'create')->name('create');
        });
        // +-+-+-+-+-+-+-+- 受注商品削除 +-+-+-+-+-+-+-+-
        Route::controller(OrderItemDeleteController::class)->prefix('order_item_delete')->name('order_item_delete.')->group(function(){
            Route::post('delete', 'delete')->name('delete');
        });
        // +-+-+-+-+-+-+-+- 過去注文から商品情報を引用 +-+-+-+-+-+-+-+-
        Route::controller(PastOrderItemController::class)->prefix('past_order_item')->name('past_order_item.')->group(function(){
            Route::get('search', 'search')->name('search');
            Route::post('reference', 'reference')->name('reference');
        });
        // +-+-+-+-+-+-+-+- 受注削除 +-+-+-+-+-+-+-+-
        Route::controller(OrderDeleteController::class)->prefix('order_delete')->name('order_delete.')->group(function(){
            Route::post('delete', 'delete')->name('delete');
        });
        // +-+-+-+-+-+-+-+- 出荷作業開始 +-+-+-+-+-+-+-+-
        Route::controller(ShippingWorkStartController::class)->prefix('shipping_work_start')->name('shipping_work_start.')->group(function(){
            Route::post('enter', 'enter')->name('enter');
        });
    });
    // +-+-+-+-+-+-+-+- 引当残ダウンロード +-+-+-+-+-+-+-+-
    Route::controller(HikiatezanDownloadController::class)->prefix('hikiatezan_download')->name('hikiatezan_download.')->group(function(){
        Route::post('download', 'download')->name('download');
    });
});