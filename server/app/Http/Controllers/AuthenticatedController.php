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
        $this->middleware('jwt.auth', 'jwt.refresh');
    }

    //General Functions
    public function getUserObject() {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return NULL;
            }
            return $user;
        } catch (Exception $e) {
            return NULL;
        }
    }

    //Route Functions  
    public function getAuthenticatedUser() {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }

    public function employees(){

    }
}
