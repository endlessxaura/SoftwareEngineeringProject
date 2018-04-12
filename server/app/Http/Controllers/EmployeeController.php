<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EmployeeController extends EmployerController
{
    //General Functions
    public function getEmployeeObject($id){
        #PRE: an employee ID FOR THE EMPLOYER
        #POST: returns the specified employee of the employer
        #NOTE: DO NOT USE THE DATABASE EMPLOYEE ID
        $employer = $this->getEmployerObject();
        if($employer != NULL){
            return DB::table('employee')->where('employer_id', $employer->employer_id)->where('employer_employee_ID', $id)->get();
        } else {
            return NULL;
        }
    }

    //Route Functions
    public function getEmployees(){
        #POST: returns all the employess of the user's employer'
        $employer = $this->getEmployerObject();
        if($employer != NULL){
            return DB::table('employee')->where('employer_id', $employer->employer_id)->get();
        } else {
            return response()->json(['failed_to_authenticate'], 404);
        }
    }

    public function getEmployee($id){
        #PRE: an employee ID FOR THE EMPLOYER
        #POST: returns the specified employee of the employer
        #NOTE: DO NOT USE THE DATABASE EMPLOYEE ID
        $employer = $this->getEmployerObject();
        if($employer != NULL){
            return DB::table('employee')->where('employer_id', $employer->employer_id)->where('employer_employee_ID', $id)->get();
        } else {
            return response()->json(['failed_to_authenticate'], 404);
        }
    }

    public function getEmployeeJobs($id){
        #PRE: an employee ID FOR THE EMPLOYER
        #POST: returns the jobs for the specified employee
        #NOTE: DO NOT USE THE DATABASE EMPLOYEE ID
        $employer = $this->getEmployerObject();
        if($employer != NULL){
            $employee = $this->getEmployeeObject();
            if($employee != NULL){
                return DB::table('employee_job')->where('employer_id', $employer->employer_id)->where('employee_id', $employee->employee_id)->get();
            }
        }
        return response()->json(['failed_to_authenticate'], 404);
    }
}
