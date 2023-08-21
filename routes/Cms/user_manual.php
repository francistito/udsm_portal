
<?php

Route::group([
    'namespace' => 'Cms',
    'prefix' => 'cms',
    'as' => 'cms.'
], function() {
    Route::group([ 'prefix' => 'user_manual',  'as' => 'user_manual.'], function() {
        Route::get('/','UserManualController@index')->name('index');
        Route::get('/menu','UserManualController@menu')->name('menu');
        Route::get('/module_groups','UserManualController@moduleGroups')->name('module_groups');
        Route::get('/module_groups/create','UserManualController@crateModuleGroup')->name('module_groups.create');
        Route::post('/module_groups/store','UserManualController@storeModuleGroup')->name('module_groups.store');
        Route::get('/module_groups/edit/{module_group}','UserManualController@editModuleGroup')->name('module_groups.edit');
        Route::put('/module_groups/update/{module_group}','UserManualController@updateModuleGroup')->name('module_groups.update');
        Route::delete('/module_groups/delete/{module_group}','UserManualController@deleteModuleGroup')->name('module_groups.delete');

        Route::get('/module/create/{module_group}','ModuleController@crateModule')->name('module.create');
        Route::post('/module/store','ModuleController@storeModule')->name('module.store');
        Route::get('/module/edit/{module}','ModuleController@editModule')->name('module.edit');
        Route::put('/module/update/{module}','ModuleController@updateModule')->name('module.update');
        Route::delete('/module/delete/{module}','ModuleController@deleteModule')->name('module.delete');

//        Route::get('/modules_by_group/{module_group}', 'UserManualController@openModulesByGroup')->name('modules_by_group');
//        Route::get('/get_module_by_group/{module_group}', 'UserManualController@getModuleByGroup')->name('get_module_by_group');
//        Route::get('/module_profile/{module}', 'UserManualController@moduleProfile')->name('module_profile');
//        Route::get('/get_module_functional_parts/{module}', 'UserManualController@getModuleFuctionalParts')->name('get_module_functional_parts');
//        Route::get('/get_module_row_by_ajax', 'UserManualController@getModuleRowByAjax')->name('get_module_row_by_ajax');
//        Route::get('/get_functional_part_row_by_ajax', 'ModuleFunctionalPartController@getFunctionalRowByAjax')->name('module_functional_part.get_functional_part_row_by_ajax');
//        Route::get('/get_search_functional_part_by_ajax', 'ModuleFunctionalPartController@getSearchFunctionalByAjax')->name('module_functional_part.get_search_functional_part_by_ajax');

    });


});



