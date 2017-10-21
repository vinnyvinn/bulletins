<?php

Auth::routes();

Route::group(['prefix' => 'super', 'as' => 'super.', 'middleware' => 'auth'], function () {
    Route::get('employee/import/payroll', '\SmoDav\Controllers\EmployeeController@importFromPayroll')->name('employee.import.payroll');
    Route::resource('employee', '\SmoDav\Controllers\EmployeeController');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('job-card/print/{id}', '\SmoDav\Controllers\API\JobCardController@printCard');
    Route::get('delivery/print/{id}', '\SmoDav\Controllers\API\DeliveryController@printNote');
    Route::get('/home', 'HomeController@index');

    Route::get('/', 'HomeController@home');


    include(__DIR__ . '/workshop.php');
    Route::get('integration/payroll/{id}', '\SmoDav\Controllers\APIIntegrationController@finalize');

    Route::post('station-selection', 'HomeController@selectStation');
    Route::post('contract-selection', 'HomeController@selectContract');

    // include(__DIR__ .'/localshunting.php');
    Route::any('/ls/{a?}/{b?}/{c?}', 'HomeController@localshunting');
    Route::any('/wsh/{a?}/{b?}/{c?}', 'HomeController@workshop');

    Route::get('/route-card/print/{id}', 'RouteCardController@show')->name('route-card-print');
    Route::any('{a}/{b?}/{c?}', 'HomeController@home');
});
