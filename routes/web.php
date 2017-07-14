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

Route::get('/test', function () {
    $items = unserialize(file_get_contents('results.txt'));
    $excel = new PHPExcel();
    $sheet = $excel->getSheet(0);
    $sheet->setTitle('Comparisons');
    $sheet->setCellValue('a1', 'Invoice ID');
    $sheet->setCellValue('b1', 'Invoice Line ID');
    $sheet->setCellValue('c1', 'SAGE Amount');
    $sheet->setCellValue('d1', 'POS Amount');
    $sheet->setCellValue('e1', 'Difference');
    $header = 'a1:e1';
    $sheet->getStyle($header)->getFill()
        ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00ffff00');
    $style = array(
        'font' => array('bold' => true,),
        'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
    );
    $sheet->getStyle($header)->applyFromArray($style);

    $sheet->fromArray($items, '', 'A2');

    $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

    $writer->setIncludeCharts(false);
    $writer->save('output.xlsx');
});

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

    Route::post('station-selection',  'HomeController@selectStation');

    // include(__DIR__ .'/localshunting.php');
    Route::any('/ls/{a?}/{b?}/{c?}', 'HomeController@localshunting');

    Route::any('{a}/{b?}/{c?}', 'HomeController@home');
});
