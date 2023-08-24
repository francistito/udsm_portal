<?php

Route::group([
    'namespace' => 'Race',
], function() {
    Route::group([ 'prefix' => 'race',  'as' => 'race.'], function() {
        Route::get('/registration', 'RaceRegistrationController@registration')->name('registration');
        Route::get('/registration_summary/{race_registration}', 'RaceRegistrationController@registrationSummary')->name('registration_summary');
        Route::post('/store', 'RaceRegistrationController@store')->name('store');
        Route::get('/change_status/{race_registration}', 'RaceRegistrationController@changeStatus')->name('change_status');
    });

});



