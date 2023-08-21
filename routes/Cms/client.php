
<?php

Route::group([
    'namespace' => 'Cms',
    'prefix' => 'cms',
    'as' => 'cms.'
], function() {


    Route::group([ 'prefix' => 'client',  'as' => 'client.'], function() {

        Route::get('/menu',[\App\Http\Controllers\Cms\ClientController::class,'menu'] )->name('menu');
        Route::get('/create', [\App\Http\Controllers\Cms\ClientController::class,'create'])->name('create');
        Route::get('/index',[\App\Http\Controllers\Cms\ClientController::class,'index'])->name('index');
        Route::post('/store', [\App\Http\Controllers\Cms\ClientController::class,'store'])->name('store');
        Route::get('/edit/{client}', [\App\Http\Controllers\Cms\ClientController::class,'edit'])->name('edit');
        Route::put('/update/{client}', [\App\Http\Controllers\Cms\ClientController::class,'update'])->name('update');
        Route::get('/profile/{client}', [\App\Http\Controllers\Cms\ClientController::class,'profile'])->name('profile');
        Route::get('/show', [\App\Http\Controllers\Cms\ClientController::class,'show'])->name('show');
        Route::delete('/delete/{client}', [\App\Http\Controllers\Cms\ClientController::class,'delete'])->name('delete');
        Route::get('/get_all_for_dt', [\App\Http\Controllers\Cms\ClientController::class,'getAllForDt'])->name('get_all_for_dt');
        Route::get('/get_for_select', [\App\Http\Controllers\Cms\ClientController::class,'getClientsForSelect'])->name('get_for_select');
        Route::post('/quick_store_from_sales', [\App\Http\Controllers\Cms\ClientController::class,'quickStoreFromSales'])->name('quick_store_from_sales');
        Route::get('/get_amount_to_loan_ajax',  [\App\Http\Controllers\Cms\ClientController::class,'getEligibleAmountToLoanAjax'])->name('get_amount_to_loan_ajax');
        Route::post('/update_max_loan_limit/{client}', [\App\Http\Controllers\Cms\ClientController::class,'updateMaxLoanLimit'] )->name('update_max_loan_limit');
        Route::get('/change_status/{client}',[\App\Http\Controllers\Cms\ClientController::class,'changeStatus'])->name('change_status');
        Route::get('/send_testimonial_link/{client}',[\App\Http\Controllers\Cms\ClientController::class,'changeStatus'])->name('send_testimonial_link');



    });

});



