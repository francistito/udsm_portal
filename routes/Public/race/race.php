<?php

Route::group([
    'namespace' => 'Race',
], function() {
    Route::group([ 'prefix' => 'race',  'as' => 'race.'], function() {
        Route::get('/index', 'RaceController@index')->name('index');
        Route::get('/registration', 'RaceController@registration')->name('index');
        Route::post('/store_registration', 'RaceController@storeRegistration')->name('store_registration');
    });

});



