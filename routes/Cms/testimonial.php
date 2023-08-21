
<?php

Route::group([
    'namespace' => 'Cms',
    'prefix' => 'cms',
    'as' => 'cms.'
], function() {

    Route::group([ 'prefix' => 'testimonial',  'as' => 'testimonial.'], function() {

        Route::get('/menu', [\App\Http\Controllers\Cms\ClientTestimonialController::class,'menu'])->name('menu');
        Route::get('/index', [\App\Http\Controllers\Cms\ClientTestimonialController::class,'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Cms\ClientTestimonialController::class,'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Cms\ClientTestimonialController::class,'store'])->name('store');
        Route::get('/edit/{client_testimonial}', [\App\Http\Controllers\Cms\ClientTestimonialController::class,'edit'])->name('edit');
        Route::put('/update/{client_testimonial}', [\App\Http\Controllers\Cms\ClientTestimonialController::class,'update'])->name('update');
        Route::get('/profile/{client_testimonial}', [\App\Http\Controllers\Cms\ClientTestimonialController::class,'profile'])->name('profile');
        Route::delete('/delete/{client_testimonial}', [\App\Http\Controllers\Cms\ClientTestimonialController::class,'delete'])->name('delete');
        Route::get('/get_all_for_dt',[\App\Http\Controllers\Cms\ClientTestimonialController::class,'getAllForDt'])->name('get_all_for_dt');
        Route::get('/change_status/{client_testimonial}','ClientTestimonialController@changeStatus')->name('change_status');

    });

});
