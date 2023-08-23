<?php

Route::group([
    'namespace' => 'Race',
], function() {
    Route::group([ 'prefix' => 'race',  'as' => 'race.'], function() {
        Route::get('/index', 'RaceRegistrationController@index')->name('index');
        Route::get('/get_all_for_dt', 'RaceRegistrationController@getAllForDt')->name('get_all_for_dt');


    });

});



