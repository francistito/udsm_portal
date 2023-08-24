<?php

Route::group([
    'namespace' => 'Race',
], function() {
    Route::group([ 'prefix' => 'race',  'as' => 'race.'], function() {
        Route::get('/index', 'RaceRegistrationController@index')->name('index');
        Route::get('/get_all_individual_for_dt', 'RaceRegistrationController@getAllIndividualForDt')->name('get_all_individual_for_dt');
        Route::get('/get_all_group_for_dt', 'RaceRegistrationController@getAllGroupForDt')->name('get_all_group_for_dt');


    });

});



