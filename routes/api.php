<?php

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

Route::middleware('auth:api')->get('/user', 'HomeController@user');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('driver/import', 'DriverController@importDrivers')->name('driver.import');
    Route::resource('driver', 'DriverController');
    Route::resource('truck', '\SmoDav\Controllers\API\VehicleController');
    Route::post('truck/import', '\SmoDav\Controllers\API\VehicleController@import')->name('truck.import');
    Route::post('route/import', 'RouteController@importDrivers')->name('route.import');
    Route::resource('route', 'RouteController');
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
    Route::resource('contract-template', 'ContractTemplateController');
    Route::get('contract-trucks/{id}', 'ContractController@ContractTrucks');

    Route::post('journey/{id}/approve', '\SmoDav\Controllers\API\JourneyController@approve');
    Route::post('journey/{id}/close', '\SmoDav\Controllers\API\JourneyController@close');
    Route::post('journey/{id}/reopen', '\SmoDav\Controllers\API\JourneyController@reopen');
    Route::resource('journey', '\SmoDav\Controllers\API\JourneyController');

    Route::get('mileage/awaiting', '\SmoDav\Controllers\API\MileageController@awaiting');
    Route::post('mileage/{id}/approve', '\SmoDav\Controllers\API\MileageController@approve');
    Route::post('mileage/{id}/close', '\SmoDav\Controllers\API\MileageController@close');
    Route::post('mileage/{id}/reopen', '\SmoDav\Controllers\API\MileageController@reopen');
    Route::resource('mileage', '\SmoDav\Controllers\API\MileageController');

    Route::resource('inspection', '\SmoDav\Controllers\API\InspectionController');
    Route::get('delivery/print/{id}', '\SmoDav\Controllers\API\DeliveryController@printNote');
    Route::get('delivery/awaiting', '\SmoDav\Controllers\API\DeliveryController@awaiting');
    Route::resource('delivery', '\SmoDav\Controllers\API\DeliveryController');
    Route::get('dashboard', '\SmoDav\Controllers\API\DashboardController@index');

    Route::get('fuel/awaiting', 'FuelController@awaiting');
    Route::resource('fuel', 'FuelController');

    Route::get('approve/{id}', 'FuelController@approve');
    Route::resource('route-card', 'RouteCardController');
    Route::get('approve_mileage/{id}', '\SmoDav\Controllers\API\MileageController@approve');
    Route::get('trucks_already_allocated/{contract_id}', '\SmoDav\Controllers\API\JourneyController@trucksAlreadyAllocated');
    Route::get('truck-report/{id}', '\SmoDav\Controllers\API\VehicleController@report');
    Route::get('new_inspection/{id}', '\SmoDav\Controllers\API\InspectionController@newInspection');

    Route::post('allocate_truck', 'ContractController@allocateTruck');
    Route::get('lscontracts', 'ContractController@lscontracts');
    Route::get('lscontractshow/{id}', 'ContractController@lscontractShow');
    Route::get('lsfuelcreate/{id}/{contract}', '\SmoDav\Controllers\API\VehicleController@lsfuelcreate');
    Route::resource('lsfuel', '\SmoDav\Controllers\API\LocalShunting\LSFuelController');
    Route::resource('lsgatepass', '\SmoDav\Controllers\API\LocalShunting\GatePassController');
    Route::resource('lsdelivery', '\SmoDav\Controllers\API\LocalShunting\LSDeliveryController');
    Route::resource('lsmileage', '\SmoDav\Controllers\API\LocalShunting\LSMileageController');
    Route::get('lsmileage/create/{truck}/{contract}', '\SmoDav\Controllers\API\LocalShunting\LSMileageController@createMileage');

    Route::resource('fuel-route', '\SmoDav\Controllers\API\FuelTruckRouteController');
    Route::get('gatepass/print/{id}', '\SmoDav\Controllers\API\GatePassController@printPass');
    Route::get('gatepass/awaiting', '\SmoDav\Controllers\API\GatePassController@awaiting');
    Route::resource('gatepass', '\SmoDav\Controllers\API\GatePassController');

    Route::resource('employee', 'RKEmployeeController');
    Route::get('unallocated_employees', 'RKEmployeeController@unallocatedEmployees');
    Route::post('allocate_employee', 'RKEmployeeController@allocateEmployee');
    Route::get('ls_mileage_employees/{id}', '\SmoDav\Controllers\API\LocalShunting\LSMileageController@lsemployeeMileage');

    Route::resource('ls_employee_mileage', '\SmoDav\Controllers\API\LocalShunting\LSEmployeeMileageController');
    Route::get('create_employee_mileage/{employee}/{contract}', 'RKEmployeeController@create_employee_mileage');

    Route::resource('employee_category', 'EmployeeCategoryController');
    Route::resource('contract_config', 'ContractConfigController');
    Route::post('config_field', 'ContractConfigController@addConfigField');
    Route::get('config_field', 'ContractConfigController@getTableFields');
    Route::delete('config_delete/{field}', 'ContractConfigController@deleteField');

    Route::post('unallocate', 'ContractController@unallocate');

    Route::get('lsdashboard','LSDashboardController@dashboard');
    Route::resource('lsreport', 'LSReportController');
    Route::get('lsloadingunloading/{id}', 'LSReportController@loadingUnloading');
    Route::get('lsfuelindex', '\SmoDav\Controllers\API\LocalShunting\LSFuelController@lsfuelindex');
    Route::get('ls/approvefuel/{id}', '\SmoDav\Controllers\API\LocalShunting\LSFuelController@approveLSFuel');
});
