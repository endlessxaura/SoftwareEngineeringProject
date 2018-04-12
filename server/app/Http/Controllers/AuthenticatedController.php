<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticatedController extends Controller
{
    /*
    * Create a new authenticated controller instanceof
    * @return void
    */
    public function __construct(){
        $this->middleware(['jwt.auth', 'jwt.refresh']);
    }

    //General Functions
    public function getUserObject() {
        //PRE: JWTAuth token
        //POST: returns the user object for the JWTAuth token
        //NOTE: THIS IS NOT A ROUTE FUNCTION
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
        //PRE: JWTAuth token
        //POST: returns the user object for the JWTAuth token
        //NOTE: THIS IS A ROUTE FUNCTION
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
}
