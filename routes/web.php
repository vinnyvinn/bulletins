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

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'HomeController@index');

    Route::get('/', function () {
        return view('home');
    });


    include(__DIR__ . '/workshop.php');
    Route::get('integration/payroll/{id}', '\SmoDav\Controllers\APIIntegrationController@finalize');

    Route::any('{a}/{b?}/{c?}', function () {
        return view('home');
    });
});
