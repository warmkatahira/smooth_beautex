<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- フィルター一覧・保存・削除 +-+-+-+-+-+-+-+-
use App\Http\Controllers\SavedFilter\SavedFilterController;

Route::middleware('common')->group(function (){
    Route::middleware(['warm_check'])->group(function () {
        // +-+-+-+-+-+-+-+- 一覧・保存・削除 +-+-+-+-+-+-+-+-
        Route::controller(SavedFilterController::class)->prefix('saved_filter')->name('saved_filter.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('create', 'create')->name('create');
            Route::post('delete', 'delete')->name('delete');
        });
    });
});