
<?php

Route::group([
    'namespace' => 'Cms',
    'prefix' => 'cms',
    'as' => 'cms.'
], function() {
    Route::group([ 'prefix' => 'blog',  'as' => 'blog.'], function() {
        Route::get('/index', [\App\Http\Controllers\Cms\BlogController::class,'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Cms\BlogController::class,'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Cms\BlogController::class,'store'])->name('store');
        Route::get('/show/{blog}', [\App\Http\Controllers\Cms\BlogController::class,'show'])->name('show');
        Route::get('/edit/{blog}', [\App\Http\Controllers\Cms\BlogController::class,'edit'])->name('edit');
        Route::put('/update/{blog}', [\App\Http\Controllers\Cms\BlogController::class,'update'])->name('update');
        Route::get('/get_all_for_dt', [\App\Http\Controllers\Cms\BlogController::class,'getAllForDt'])->name('get_all_for_dt');

        Route::get('/view_blog/{blog}', [\App\Http\Controllers\Cms\BlogController::class,'viewBlog'])->name('view_blog');

        Route::post('/delete','BlogController@delete')->name('delete');
        Route::get('/get_blog_by_id_for_edit', 'BlogController@getBlogByIdForEdit')->name('get_blog_by_id_for_edit');
        Route::post('/upload_tempo_files', 'BlogController@uploadTemporaryFiles')->name('upload_tempo_files');
        Route::get('/publish/{blog}', 'BlogController@publish')->name('publish');
        Route::get('/change_status/{blog}', 'BlogController@changeStatus')->name('change_status');


    });
});



