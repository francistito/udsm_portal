<?php

Route::group([
    'namespace' => 'Cms',
    'prefix' => 'cms',
    'as'=> 'cms.'
], function() {
    Route::group([ 'prefix' => 'dashboard',  'as' => 'dashboard.'], function() {
        Route::get('/', [\App\Http\Controllers\Cms\DashboardController::class,'index'])->name('index');
    });
});


