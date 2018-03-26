<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthenticatedController extends Controller
{
    /*
    * Create a new authenticated controller instanceof
    * @return void
    */
    public function __construct(){
        $this->middleware('jwt.auth');
    }

    public function test(){
        return "GOOD JOB";
    }
}
