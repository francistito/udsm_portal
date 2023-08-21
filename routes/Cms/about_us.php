<?php

Route::group([
    'namespace' => 'Cms',
    'prefix' => 'cms',
    'as' => 'cms.'
], function() {
    Route::group([ 'prefix' => 'about_us',  'as' => 'about_us.'], function() {
        Route::get('/index', [\App\Http\Controllers\Cms\AboutUsController::class,'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Cms\AboutUsController::class,'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Cms\AboutUsController::class,'store'])->name('store');
        Route::put('/update/{about_us}', [\App\Http\Controllers\Cms\AboutUsController::class,'update'])->name('update');
        Route::get('/profile/{about_us}',  [\App\Http\Controllers\Cms\AboutUsController::class,'profile'])->name('profile');
        Route::get('/edit/{about_us}',  [\App\Http\Controllers\Cms\AboutUsController::class,'edit'])->name('edit');
        Route::get('/delete/{about_us}',  [\App\Http\Controllers\Cms\AboutUsController::class,'destroy'])->name('delete');
        Route::get('/profile/{about_us}',  [\App\Http\Controllers\Cms\AboutUsController::class,'profile'])->name('profile');
        Route::get('/get/admin_data',  [\App\Http\Controllers\Cms\AboutUsController::class,'getForAdminDatatable'])->name('get.admin_data');
    });

});
