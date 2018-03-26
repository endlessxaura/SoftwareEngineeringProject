<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use JWTAuth;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Hash;

class AuthController extends Controller
{
    /*
    * Create a new authentication controller instanceof
    * @return void
    */
    public function __construct(){
        $this->middleware('guest', ['except' => 'getLogout']);
    }
    
    public function authenticate(Request $request){
        #PRE: takes a request for authentication
        #POST: returns a JWT for use
        $credentials = $request->only('username', 'password');

        try {
            if(!$token = JWTAuth::attempt($credentials)){
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to authenticate'], 500);
        }

        return response()->json(compact('token'));
    }
}
