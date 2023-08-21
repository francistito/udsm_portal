<?php

Route::group([
    'namespace' => 'Product',
], function() {


    Route::group([ 'prefix' => 'product/demo/',  'as' => 'product.demo.'], function() {
        Route::get('/view_demo',[\App\Http\Controllers\Product\ProductDemoController::class,'ViewDemo'])->name('view_demo');

    });

});



