<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Trip;
use Carbon\Carbon;
Auth::routes();

Route::group(['prefix' => 'super', 'as' => 'super.', 'middleware' => 'auth'], function () {
    Route::get('employee/import/payroll', '\SmoDav\Controllers\EmployeeController@importFromPayroll')->name('employee.import.payroll');
    Route::resource('employee', '\SmoDav\Controllers\EmployeeController');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('delivery/print/{id}', '\SmoDav\Controllers\API\DeliveryController@printNote');
    Route::get('/home', 'HomeController@index');

    Route::get('/', 'HomeController@home');


    include(__DIR__ . '/workshop.php');
    Route::get('integration/payroll/{id}', '\SmoDav\Controllers\APIIntegrationController@finalize');

    Route::any('{a}/{b?}/{c?}', 'HomeController@home');
});
