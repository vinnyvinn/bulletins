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

    Route::get('module-udfs/{module}', 'UDFController@moduleUdf')->name('module-udfs');
    Route::get('download-doc/{file}', 'UDFController@download')->name('download');

    Route::resource('job-card', '\SmoDav\Controllers\API\JobCardController');
    Route::post('job-card/{id}/approve', '\SmoDav\Controllers\API\JobCardController@approveJobCard');
    Route::post('job-card/{id}/disapprove', '\SmoDav\Controllers\API\JobCardController@disapproveJobCard');
    Route::post('job-card/{id}/close', '\SmoDav\Controllers\API\JobCardController@closeCard');
    Route::resource('parts', '\SmoDav\Controllers\API\PartsController');
    Route::post('parts/{id}/approve', '\SmoDav\Controllers\API\PartsController@approve');
    Route::post('parts/{id}/consume', '\SmoDav\Controllers\API\PartsController@consume');
    Route::post('parts/{id}/disapprove', '\SmoDav\Controllers\API\PartsController@disapprove');


    Route::post('contract/{id}/approve', 'ContractController@approve');
    Route::post('contract/{id}/close', 'ContractController@close');
    Route::post('contract/{id}/reopen', 'ContractController@reopen');
    Route::resource('contract', 'ContractController');
    Route::post('journey/{id}/approve', '\SmoDav\Controllers\API\JourneyController@approve');
    Route::post('journey/{id}/close', '\SmoDav\Controllers\API\JourneyController@close');
    Route::post('journey/{id}/reopen', '\SmoDav\Controllers\API\JourneyController@reopen');
    Route::resource('journey', '\SmoDav\Controllers\API\JourneyController');

    Route::post('mileage/{id}/approve', '\SmoDav\Controllers\API\MileageController@approve');
    Route::post('mileage/{id}/close', '\SmoDav\Controllers\API\MileageController@close');
    Route::post('mileage/{id}/reopen', '\SmoDav\Controllers\API\MileageController@reopen');
    Route::resource('mileage', '\SmoDav\Controllers\API\MileageController');

    Route::resource('inspection', '\SmoDav\Controllers\API\InspectionController');
    Route::resource('delivery', '\SmoDav\Controllers\API\DeliveryController');
    Route::get('dashboard', '\SmoDav\Controllers\API\DashboardController@index');
    Route::resource('fuel', 'FuelController');
    Route::get('approve/{id}', 'FuelController@approve');
    Route::resource('route-card', 'RouteCardController');
});
