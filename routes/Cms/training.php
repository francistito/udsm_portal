<?php

Route::group([
    'namespace' => 'Cms',
    'prefix' => 'cms',
    'as' => 'cms.'
], function() {
    Route::group([ 'prefix' => 'training',  'as' => 'training.'], function() {
        Route::get('/index', [\App\Http\Controllers\Cms\TrainingController::class,'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Cms\TrainingController::class,'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Cms\TrainingController::class,'store'])->name('store');
        Route::put('/update/{training}', [\App\Http\Controllers\Cms\TrainingController::class,'update'])->name('update');
        Route::get('/profile/{training}',  [\App\Http\Controllers\Cms\TrainingController::class,'profile'])->name('profile');
        Route::get('/edit/{training}',  [\App\Http\Controllers\Cms\TrainingController::class,'edit'])->name('edit');
        Route::get('/delete/{training}',  [\App\Http\Controllers\Cms\TrainingController::class,'destroy'])->name('delete');
        Route::get('/profile/{training}',  [\App\Http\Controllers\Cms\TrainingController::class,'profile'])->name('profile');
        Route::get('/get/admin_data',  [\App\Http\Controllers\Cms\TrainingController::class,'getForAdminDatatable'])->name('get.admin_data');
    });

});
