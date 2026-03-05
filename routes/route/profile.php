<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- プロフィール +-+-+-+-+-+-+-+-
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\ProfileImageUpdateController;

Route::middleware('common')->group(function (){
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function(){
        Route::get('', 'index')->name('index');
    });
    Route::controller(ProfileImageUpdateController::class)->prefix('profile_image_update')->name('profile_image_update.')->group(function(){
        Route::post('update', 'update')->name('update');
    });
});