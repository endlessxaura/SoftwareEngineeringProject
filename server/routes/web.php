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