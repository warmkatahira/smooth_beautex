<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- 出荷メニュー +-+-+-+-+-+-+-+-
use App\Http\Controllers\Shipping\ShippingMenu\ShippingMenuController;
// +-+-+-+-+-+-+-+- 出荷管理 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Shipping\ShippingMgt\ShippingMgtController;
// +-+-+-+-+-+-+-+- 配送伝票番号取込 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Shipping\TrackingNoImport\TrackingNoImportController;
// +-+-+-+-+-+-+-+- 出荷グループ更新 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Shipping\ShippingGroupUpdate\ShippingGroupUpdateController;
// +-+-+-+-+-+-+-+- 出荷待ちへ戻す +-+-+-+-+-+-+-+-
use App\Http\Controllers\Shipping\ReturnToShukkaMachi\ReturnToShukkaMachiController;
// +-+-+-+-+-+-+-+- 出荷検品実績削除 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Shipping\ShippingInspectionActualDelete\ShippingInspectionActualDeleteController;
// +-+-+-+-+-+-+-+- トータルピッキングリスト +-+-+-+-+-+-+-+-
use App\Http\Controllers\Shipping\TotalPickingList\TotalPickingListController;
// +-+-+-+-+-+-+-+- 受注単位帳票 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Shipping\OrderDocument\OrderDocumentController;
// +-+-+-+-+-+-+-+- 納品書 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Shipping\DeliveryNote\DeliveryNoteCreateController;
// +-+-+-+-+-+-+-+- 荷札データ +-+-+-+-+-+-+-+-
use App\Http\Controllers\Shipping\Nifuda\NifudaCreateController;
use App\Http\Controllers\Shipping\Nifuda\NifudaDownloadController;
// +-+-+-+-+-+-+-+- 出荷検品 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Shipping\ShippingInspection\ShippingInspectionController;
// +-+-+-+-+-+-+-+- 出荷完了 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Shipping\ShippingWorkEnd\ShippingWorkEndController;
// +-+-+-+-+-+-+-+- 出荷完了履歴 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Shipping\ShippingWorkEndHistory\ShippingWorkEndHistoryController;
// +-+-+-+-+-+-+-+- 出荷履歴 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Shipping\ShippingHistory\ShippingHistoryController;
use App\Http\Controllers\Shipping\ShippingHistory\ShippingHistoryDownloadController;
use App\Http\Controllers\Shipping\ShippingHistory\ShippingActualDownloadController;

Route::middleware('common')->group(function (){
    // +-+-+-+-+-+-+-+- 出荷メニュー +-+-+-+-+-+-+-+-
    Route::controller(ShippingMenuController::class)->prefix('shipping_menu')->name('shipping_menu.')->group(function(){
        Route::get('', 'index')->name('index');
    });
    // +-+-+-+-+-+-+-+- 出荷管理 +-+-+-+-+-+-+-+-
    Route::controller(ShippingMgtController::class)->prefix('shipping_mgt')->name('shipping_mgt.')->group(function(){
        Route::get('', 'index')->name('index');
    });
    Route::middleware(['warm_check'])->group(function () {
        // +-+-+-+-+-+-+-+- 出荷管理 +-+-+-+-+-+-+-+-
        Route::controller(TrackingNoImportController::class)->prefix('tracking_no_import')->name('tracking_no_import.')->group(function(){
            Route::post('import', 'import')->name('import');
        });
        // +-+-+-+-+-+-+-+- 出荷グループ更新 +-+-+-+-+-+-+-+-
        Route::controller(ShippingGroupUpdateController::class)->prefix('shipping_group_update')->name('shipping_group_update.')->group(function(){
            Route::post('update', 'update')->name('update');
        });
        // +-+-+-+-+-+-+-+- 出荷待ちへ戻す +-+-+-+-+-+-+-+-
        Route::controller(ReturnToShukkaMachiController::class)->prefix('return_to_shukka_machi')->name('return_to_shukka_machi.')->group(function(){
            Route::post('enter', 'enter')->name('enter');
        });
        // +-+-+-+-+-+-+-+- 出荷検品実績削除 +-+-+-+-+-+-+-+-
        Route::controller(ShippingInspectionActualDeleteController::class)->prefix('shipping_inspection_actual_delete')->name('shipping_inspection_actual_delete.')->group(function(){
            Route::post('delete', 'delete')->name('delete');
        });
        // +-+-+-+-+-+-+-+- トータルピッキングリスト +-+-+-+-+-+-+-+-
        Route::controller(TotalPickingListController::class)->prefix('total_picking_list_create')->name('total_picking_list_create.')->group(function(){
            Route::get('create', 'create')->name('create');
        });
        // +-+-+-+-+-+-+-+- 受注単位帳票 +-+-+-+-+-+-+-+-
        Route::controller(OrderDocumentController::class)->prefix('order_document')->name('order_document.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        // +-+-+-+-+-+-+-+- 納品書 +-+-+-+-+-+-+-+-
        Route::controller(DeliveryNoteCreateController::class)->prefix('delivery_note_create')->name('delivery_note_create.')->group(function(){
            Route::get('create', 'create')->name('create');
            Route::get('create_specify_order', 'create_specify_order')->name('create_specify_order');
        });
        // +-+-+-+-+-+-+-+- 荷札データ +-+-+-+-+-+-+-+-
        Route::controller(NifudaCreateController::class)->prefix('nifuda_create')->name('nifuda_create.')->group(function(){
            Route::get('create', 'create')->name('create');
        });
        Route::controller(NifudaDownloadController::class)->prefix('nifuda_download')->name('nifuda_download.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::get('download', 'download')->name('download');
        });
        // +-+-+-+-+-+-+-+- 出荷検品 +-+-+-+-+-+-+-+-
        Route::controller(ShippingInspectionController::class)->prefix('shipping_inspection')->name('shipping_inspection.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('ajax_check_order_control_id', 'ajax_check_order_control_id');
            Route::post('ajax_check_tracking_no', 'ajax_check_tracking_no');
            Route::post('ajax_check_item_id_code', 'ajax_check_item_id_code');
            Route::post('ajax_check_lot', 'ajax_check_lot');
            Route::post('complete', 'complete')->name('complete');
        });
        // +-+-+-+-+-+-+-+- 出荷完了 +-+-+-+-+-+-+-+-
        Route::controller(ShippingWorkEndController::class)->prefix('shipping_work_end')->name('shipping_work_end.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('enter', 'enter')->name('enter');
        });
        // +-+-+-+-+-+-+-+- 出荷完了 +-+-+-+-+-+-+-+-
        Route::controller(ShippingWorkEndHistoryController::class)->prefix('shipping_work_end_history')->name('shipping_work_end_history.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        // +-+-+-+-+-+-+-+- 出荷履歴 +-+-+-+-+-+-+-+-
        Route::controller(ShippingHistoryController::class)->prefix('shipping_history')->name('shipping_history.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(ShippingHistoryDownloadController::class)->prefix('shipping_history_download')->name('shipping_history_download.')->group(function(){
            Route::get('download', 'download')->name('download');
        });
        Route::controller(ShippingActualDownloadController::class)->prefix('shipping_actual_download')->name('shipping_actual_download.')->group(function(){
            Route::get('download', 'download')->name('download');
        });
    });
});