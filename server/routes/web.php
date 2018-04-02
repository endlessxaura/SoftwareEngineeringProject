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
Route::post('/getPassword', function(Request $request){
     return Hash::make($request->password);
});

Route::post('/authenticate', 'AuthController@authenticate');

Route::get('/test', 'AuthenticatedController@test');

//Employer
Route::get('/employer/{employer_id}', function ($employer_id){
    $employer = DB::table('employer')->where('employer_id', $employer_id)->first();
    return json_encode($employer);
});

Route::get('/employer/{employer_id}/employees', function ($employer_id){
    $employees = DB::table('employee')->where('employer_id', $employer_id)->get();
    return json_encode($employees);
});

Route::get('/employer/{employer_id}/jobs', function($employer_id){
    $jobs = DB::table('job')->where('employer_id', $employer_id)->get();
    return json_encode($jobs);
});

//Employee
Route::get('employee/{employee_id}', function($employee_id){
    $employee = DB::table('employee')->where('employee_id', $employee_id)->first();
    return json_encode($employee);
});