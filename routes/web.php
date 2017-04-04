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

Route::get('/', function () {
    return view('home');
});

Route::get('test', function () {
    $trip = Trip::where('trip_date', Carbon::now())
        ->where('truck_id', 1)
        ->where('is_complete', false)
        ->first();

    $delnote = $trip->deliveryNote;
    $delnote->fields = json_decode($delnote->fields);
    $trip->deliveryNote = $delnote;

//    dd($delnote);

    return view('printouts.deliverynote')->with('trip', $trip)->render();
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::any('{a}/{b?}/{c?}', function () {
    return view('home');
});


