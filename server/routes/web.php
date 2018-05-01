<?php

use Illuminate\Http\Request;

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

Route::get('/', function () {
    return view('welcome');
});

//Authentication
Route::post('/authenticate', 'AuthController@authenticate');

Route::get('/user', 'AuthenticatedController@getAuthenticatedUser');

//Employer
Route::get('/employer', 'EmployerController@getEmployer');

//Employee
Route::get('/employees', 'EmployeeController@getEmployees');

Route::get('/employee/{id}', 'EmployeeController@getEmployee');

Route::get('/employee/{id}/jobs', 'EmployeeController@getEmployeeJobs');

//Jobs
Route::get('/jobs', 'JobController@getJobs');

Route::get('/job/{id}', 'JobController@getJob');

Route::get('/job/{id}/employees', 'JobController@getJobEmployees');

//Hours
Route::get('/hours', 'HourController@getHours');

Route::post('/hours', 'HourController@postHours');

Route::post('/hours/update', 'HourController@updateHours');

Route::post('/hours/delete', 'HourController@deleteHours');

//Batch files
Route::post('/batchFile', 'BatchFileController@processFile');

Route::get('/batchFile', 'BatchFileController@sendFile');