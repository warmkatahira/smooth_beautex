<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- 受注取込 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\OrderImport\OrderImportController;
// +-+-+-+-+-+-+-+- 受注管理 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\OrderMgt\OrderMgtController;
// +-+-+-+-+-+-+-+- 受注詳細 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\OrderDetail\OrderDetailController;
use App\Http\Controllers\Order\OrderDetail\OrderDetailUpdateController;
// +-+-+-+-+-+-+-+- 受注商品分割 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\OrderItemSplit\OrderItemSplitController;
// +-+-+-+-+-+-+-+- 受注商品追加 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\OrderItem\OrderItemCreateController;
// +-+-+-+-+-+-+-+- 受注商品削除 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\OrderItem\OrderItemDeleteController;
// +-+-+-+-+-+-+-+- 受注商品削除 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\OrderItem\PastOrderItemController;
// +-+-+-+-+-+-+-+- 出荷検品ロット更新 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\OrderItemLot\OrderItemLotUpdateController;
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
            Route::post('desired_delivery_date', 'desired_delivery_date')->name('desired_delivery_date');
            Route::post('is_stock_allocation_skipped', 'is_stock_allocation_skipped')->name('is_stock_allocation_skipped');
            Route::post('is_shipping_inspection_skipped', 'is_shipping_inspection_skipped')->name('is_shipping_inspection_skipped');
        });
        // +-+-+-+-+-+-+-+- 受注商品分割 +-+-+-+-+-+-+-+-
        Route::controller(OrderItemSplitController::class)->prefix('order_item_split')->name('order_item_split.')->group(function(){
            Route::get('split_preview', 'split_preview')->name('split_preview');
            Route::post('split', 'split')->name('split');
        });
        // +-+-+-+-+-+-+-+- 過去注文から商品情報を引用 +-+-+-+-+-+-+-+-
        Route::controller(PastOrderItemController::class)->prefix('past_order_item')->name('past_order_item.')->group(function(){
            Route::get('search', 'search')->name('search');
            Route::post('reference', 'reference')->name('reference');
        });
        // +-+-+-+-+-+-+-+- 出荷検品ロット更新 +-+-+-+-+-+-+-+-
        Route::controller(OrderItemLotUpdateController::class)->prefix('order_item_lot_update')->name('order_item_lot_update.')->group(function(){
            Route::post('update', 'update')->name('update');
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
    // +-+-+-+-+-+-+-+- 受注商品追加 +-+-+-+-+-+-+-+-
    Route::controller(OrderItemCreateController::class)->prefix('order_item_create')->name('order_item_create.')->group(function(){
        Route::get('search', 'search')->name('search');
        Route::post('create', 'create')->name('create');
    });
    // +-+-+-+-+-+-+-+- 受注商品削除 +-+-+-+-+-+-+-+-
    Route::controller(OrderItemDeleteController::class)->prefix('order_item_delete')->name('order_item_delete.')->group(function(){
        Route::post('delete', 'delete')->name('delete');
    });
    // +-+-+-+-+-+-+-+- 配送先郵便番号+配送先住所+補足事項更新 +-+-+-+-+-+-+-+-
    Route::controller(OrderDetailUpdateController::class)->prefix('order_detail_update')->name('order_detail_update.')->group(function(){
        Route::post('ship_zip_code', 'ship_zip_code')->name('ship_zip_code');
        Route::post('ship_address', 'ship_address')->name('ship_address');
        Route::post('supplement', 'supplement')->name('supplement');
    });
    // +-+-+-+-+-+-+-+- 引当残ダウンロード +-+-+-+-+-+-+-+-
    Route::controller(HikiatezanDownloadController::class)->prefix('hikiatezan_download')->name('hikiatezan_download.')->group(function(){
        Route::post('download', 'download')->name('download');
    });
});