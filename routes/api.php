<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return Response::json([
        'user' => $request->user()
    ]);
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::resource('driver', 'DriverController');
    Route::post('driver/import', 'DriverController@importDrivers')->name('driver.import');
    Route::resource('route', 'RouteController');
    Route::post('route/import', 'RouteController@importDrivers')->name('route.import');
    Route::resource('truck', 'TruckController');
    Route::post('truck/import', 'TruckController@import')->name('truck.import');
    Route::resource('trailer', 'TrailerController');
    Route::post('trailer/import', 'TrailerController@import')->name('trailer.import');
    Route::resource('contract', 'ContractController');
    Route::resource('allocation', 'AllocationController');
    Route::post('progress/{id}', 'ProgressController@progress');
    Route::get('progress/{id}', 'TruckController@getAtStage');

    Route::get('trip/{id}', 'ProgressController@getTrip');
    Route::post('loading/{id}', 'ProgressController@loading');
    Route::post('enroute/{id}', 'ProgressController@enroute');
    Route::post('offloading/{id}', 'ProgressController@offloading');
    Route::post('in-yard/{id}', 'ProgressController@inYard');

    Route::resource('users', 'UserController');
    Route::resource('udf', 'UDFController');
    Route::post('module-udf/{module}', 'RouteController@getModuleUdfs')->name('module-udf');

});
