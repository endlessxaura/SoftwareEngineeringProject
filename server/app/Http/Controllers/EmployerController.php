<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EmployerController extends AuthenticatedController
{
    //General Functions
    public function getEmployerObject(){
        //PRE: an authenticated user
        //POST: returns a JSON of the employer for the user
        //NOTE: THIS iS NOT A ROUTE FUNCTION
        $user = $this->getUserObject();
        if($user != NULL) {
            return DB::table('employer')->where('employer_id', $user->employer_id)->first();
        } else {
            return NULL;
        }
    }

    //Route Functions
    public function getEmployer(){
        //PRE: an authenticated user
        //POST: returns a JSON of the employer for the user
        //NOTE: THIS iS A ROUTE FUNCTION
        $user = $this->getUserObject();
        if($user != NULL) {
            return DB::table('employer')->where('employer_id', $user->employer_id)->first();
        } else {
            return response()->json(['failed_to_authenticate'], 404);
        }
    }
}
