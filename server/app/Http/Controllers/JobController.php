<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class JobController extends EmployerController
{
    //General functtions
    public function getJobObject ($id) { 
        #PRE: $id is the job_id of the object
        #POST: returns the specified job or NULL
        $employer = $this->getEmployerObject();
        if($employer != NULL){
            return DB::table('job')->where('employer_id', $employer->employer_id)->where('job_id', $id)->first();
        } else {
            return NULL;
        }
    }

    //Route functions
    public function getJobs () {
        #POST: returns all the jobs of the user's employer
        $employer = $this->getEmployerObject();
        if($employer != NULL){
            return DB::table('job')->where('employer_id', $employer->employer_id)->get();
        } else {
            return response()->json(['failed_to_authenticate'], 401);
        }
    }

    public function getJob ($id) {
        #PRE: $id is the job_id of the object
        #POST: returns the specified job or an error response
        $employer = $this->getEmployerObject();
        if($employer != NULL){
            return DB::table('job')->where('employer_id', $employer->employer_id)->where('job_id', $id)->get();
        } else {
            return response()->json(['failed_to_authenticate'], 401);
        }
    }

    public function getJobEmployees ($id) {
        #PRE: $id is the job_id in question
        #POST: returns all the employees for the specified job
        $employer = $this->getEmployerObject ();
        if($employer != NULL){
            $job = $this->getJobObject ($id);
            if($job != NULL){
                $relation = DB::table('employee_job')->where('employer_id', $employer->employer_id)->where('job_id', $job->job_id)->get();
                $employeeQueries = [];
                for ($i = 0; $i < sizeof($relation); $i++){
                    $job = DB::table('employee')->where("employee_id", $relation[$i]->employee_id)->first();
                    $employeeQueries[] = $job;
                }
                return $employeeQueries;
            }
        }
        return response()->json(['failed_to_authenticate'], 401);
    }
}
