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
    return $request->user();
});

Route::resource('driver', 'DriverController');
Route::resource('route', 'RouteController');
Route::resource('truck', 'TruckController');
Route::resource('contract', 'ContractController');
Route::resource('allocation', 'AllocationController');
Route::post('progress/{id}', 'TruckController@progress');
Route::get('progress', 'TruckController@allocated');
