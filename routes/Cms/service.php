<?php

Route::group([
    'namespace' => 'Cms',
    'prefix' => 'cms',
    'as' => 'cms.'
], function() {
    Route::group([ 'prefix' => 'service',  'as' => 'service.'], function() {
        Route::get('/index', [\App\Http\Controllers\Cms\ServiceController::class,'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Cms\ServiceController::class,'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Cms\ServiceController::class,'store'])->name('store');
        Route::put('/update/{service}', [\App\Http\Controllers\Cms\ServiceController::class,'update'])->name('update');
        Route::get('/profile/{service}',  [\App\Http\Controllers\Cms\ServiceController::class,'profile'])->name('profile');
        Route::get('/edit/{service}',  [\App\Http\Controllers\Cms\ServiceController::class,'edit'])->name('edit');
        Route::get('/delete/{service}',  [\App\Http\Controllers\Cms\ServiceController::class,'destroy'])->name('delete');
        Route::get('/profile/{service}',  [\App\Http\Controllers\Cms\ServiceController::class,'profile'])->name('profile');
        Route::get('/get/admin_data',  [\App\Http\Controllers\Cms\ServiceController::class,'getForAdminDatatable'])->name('get.admin_data');
    });

});
