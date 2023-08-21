<?php

Route::group([
    'namespace' => 'Race',
    'prefix' => 'race',
], function() {
    Route::group([ 'prefix' => 'race',  'as' => 'race.'], function() {
        Route::get('/index', 'RaceController@index')->name('index');
        Route::get('/registration', 'RaceController@registration')->name('index');
    });

});



