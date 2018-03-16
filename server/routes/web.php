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

Route::post('/API/employee/hours', function (Request $request) {
    return $request->all();
});

Route::get('/API/employee/{id}', function (Request $request){
    return "TODO";
});

Route::get('/API/employee/all', function (Request $request){
    return "TODO";
});