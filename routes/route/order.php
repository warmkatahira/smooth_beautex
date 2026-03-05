<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- 受注メニュー +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\OrderMenu\OrderMenuController;
// +-+-+-+-+-+-+-+- 受注取込 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\OrderImport\OrderImportController;
// +-+-+-+-+-+-+-+- 受注管理 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\OrderMgt\OrderMgtController;
// +-+-+-+-+-+-+-+- 受注詳細 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\OrderDetail\OrderDetailController;
use App\Http\Controllers\Order\OrderDetail\OrderDetailUpdateController;
// +-+-+-+-+-+-+-+- 受注削除 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\OrderDelete\OrderDeleteController;
// +-+-+-+-+-+-+-+- 出荷作業開始 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\ShippingWorkStart\ShippingWorkStartController;
// +-+-+-+-+-+-+-+- 確認待ちリスト +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\KakuninmachiList\KakuninmachiListController;
// +-+-+-+-+-+-+-+- 引当待ちリスト +-+-+-+-+-+-+-+-
use App\Http\Controllers\Order\HikiatemachiList\HikiatemachiListController;

Route::middleware('common')->group(function (){
    // +-+-+-+-+-+-+-+- 受注メニュー +-+-+-+-+-+-+-+-
    Route::controller(OrderMenuController::class)->prefix('order_menu')->name('order_menu.')->group(function(){
        Route::get('', 'index')->name('index');
    });
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
            Route::post('order_memo', 'order_memo')->name('order_memo');
            Route::post('shipping_work_memo', 'shipping_work_memo')->name('shipping_work_memo');
            Route::post('desired_delivery_date', 'desired_delivery_date')->name('desired_delivery_date');
        });
        // +-+-+-+-+-+-+-+- 受注削除 +-+-+-+-+-+-+-+-
        Route::controller(OrderDeleteController::class)->prefix('order_delete')->name('order_delete.')->group(function(){
            Route::post('delete', 'delete')->name('delete');
        });
        // +-+-+-+-+-+-+-+- 出荷作業開始 +-+-+-+-+-+-+-+-
        Route::controller(ShippingWorkStartController::class)->prefix('shipping_work_start')->name('shipping_work_start.')->group(function(){
            Route::post('enter', 'enter')->name('enter');
        });
        // +-+-+-+-+-+-+-+- 確認待ちリスト +-+-+-+-+-+-+-+-
        Route::controller(KakuninmachiListController::class)->prefix('kakuninmachi_list')->name('kakuninmachi_list.')->group(function(){
            Route::get('create', 'create')->name('create');
        });
        // +-+-+-+-+-+-+-+- 引当待ちリスト +-+-+-+-+-+-+-+-
        Route::controller(HikiatemachiListController::class)->prefix('hikiatemachi_list')->name('hikiatemachi_list.')->group(function(){
            Route::get('create', 'create')->name('create');
        });
    });
});