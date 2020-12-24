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
//login
Route::get('/login', ['as' => 'login', 'uses' => 'AuthController@index']);
Route::post('/postlogin', ['as' => 'post_login', 'uses' => 'AuthController@authenticate']);
Route::get('/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
// middleware

Route::group(['middleware' => 'authapi'], function(){
        // set config 
    Route::get('/setconfig', ['as' => 'set_config', 'uses' => 'SetConfigController@index']);
    Route::post('/setconfig/post', ['as' => 'set_config_post', 'uses' => 'SetConfigController@post']);
    // home
    Route::group(['middleware' => 'setconfig'], function(){
        Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
        Route::get('/test', ['as' => 'test', 'uses' => 'HomeController@test']);


        //Route::get('/employee',['as' => 'employee', 'uses' => 'HomeController@employee_index']);
        Route::get('/get_employee',['as' => 'get_employee', 'uses' => 'HomeController@employee_get']);

        Route::get('/employee',['as' => 'employee_raw', 'uses' => 'HomeController@employeeraw']);
        Route::post('/employeerawpost',['as' => 'post_employee_raw', 'uses' => 'HomeController@Postemployeeraw']);

        //report
        Route::get('/excel-report-today', ['as' => 'excel_report_today', 'uses' => 'ReportController@ExcelReport']);
        Route::get('/excel-report/{time_start}/{time_end}/{thermal}', ['as' => 'excel_report_time', 'uses' => 'ReportController@ExcelReportTimeRange']);
        Route::post('/pass-variable', ['as' => 'pass_variable', 'uses' => 'ReportController@PassVar']);

        Route::post('/pass-variable_raw', ['as' => 'pass_variable_raw', 'uses' => 'ReportController@PassVarRaw']);
        Route::get('/excel-report-today-raw', ['as' => 'excel_report_today_raw', 'uses' => 'ReportController@ExcelReportRaw']);
        Route::get('/excel-report-raw/{time_start}/{time_end}/{thermal}', ['as' => 'excel_report_time_raw', 'uses' => 'ReportController@ExcelReportTimeRangeRaw']);
        //system
        // Route::get('/system-config', ['as' => 'sys_conf', 'uses' => 'SystemController@index']);
        // Route::post('/system-config/post', ['as' => 'sys_conf_post', 'uses' => 'SystemController@post']);

        //home field

        Route::get('/get-today-count',['as' => 'count_today', 'uses' => 'HomeController@getTodayCount']);

        //sync
        Route::get('/sync', ['as' => 'sync', 'uses' => 'SyncController@index']);
        Route::get('/ther_library',['as' => 'ther_library', 'uses' => 'SyncController@getLibrary']);
        Route::post('/post_sync',['as' => 'post_sync', 'uses' => 'SyncController@Sync']);

        // permiss
        Route::get('/permiss',['as' => 'permiss', 'uses' => 'PermissionController@index']);
        Route::get('/subject_permission',['as' => 'subject_permiss', 'uses' => 'SubjectPermisionController@index']);

        // quyền 
        Route::post('/department_in_group',['as' => 'dp_in_gr', 'uses' => 'PermissionController@GetDPinGR']);
        Route::post('/save_dp',['as' => 'save_dp', 'uses' => 'PermissionController@SaveDepartmentInGroup']);

        // subject premission
        Route::post('/subject_in_group',['as' => 'sj_in_gr', 'uses' => 'SubjectPermisionController@GetSJinGR']);
        Route::post('/save_subject',['as' => 'save_sj', 'uses' => 'SubjectPermisionController@SaveSubjectInGroup']);


    });

});


