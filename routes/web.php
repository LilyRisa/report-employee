<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// home
Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
Route::get('/test', ['as' => 'test', 'uses' => 'HomeController@test']);
Route::get('/employee',['as' => 'employee', 'uses' => 'HomeController@employee_index']);
Route::get('/get_employee',['as' => 'get_employee', 'uses' => 'HomeController@employee_get']);
Route::get('/rawemployee',['as' => 'employee_raw', 'uses' => 'HomeController@employeeraw']);
Route::post('/employeerawpost',['as' => 'post_employee_raw', 'uses' => 'HomeController@Postemployeeraw']);

//report
Route::get('/excel-report-today', ['as' => 'excel_report_today', 'uses' => 'ReportController@ExcelReport']);
Route::get('/excel-report/{time_start}/{time_end}', ['as' => 'excel_report_time', 'uses' => 'ReportController@ExcelReportTimeRange']);
Route::post('/pass-variable', ['as' => 'pass_variable', 'uses' => 'ReportController@PassVar']);
//system
Route::get('/system-config', ['as' => 'sys_conf', 'uses' => 'SystemController@index']);
Route::post('/system-config/post', ['as' => 'sys_conf_post', 'uses' => 'SystemController@post']);
Route::get('/sync', ['as' => 'sync', 'uses' => 'SyncController@index']);
//home field

Route::get('/get-today-count',['as' => 'count_today', 'uses' => 'HomeController@getTodayCount']);