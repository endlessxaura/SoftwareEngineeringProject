<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HourController extends JobController
{
    //General functions
    function getHoursObject (Request $request, $onlyOne = FALSE) {
        #POST: returns a specified hours object
        #NOTE: It will return the FIRST one it finds
        $employer = $this->getEmployerObject();
        if ($employer != NULL) {
            if($onlyOne){
                if ($request->has('employee_id') &&
                    $request->has('job_id') &&
                    $request->has('date') &&
                    $request->has('shift_number') &&
                    ($request->has('quantity') || ($request->has('start') && $request->has('end')))) 
                {
                    $query = DB::table('hours')->where('employer_id', $employer->employer_id);
                    $query = $query->where('employee_id', $request->employee_id);
                    $query = $query->where('job_id', $request->job_id);
                    $query = $query->where('date', $request->date);
                    $query = $query->where('shift_number', $request->shift_number);
                    return $query->first();
                } else {
                    return NULL;
                }
            } else {
                $query = DB::table('hours')->where('employer_id', $employer->employer_id);
                if ($request->has('employee_id')) {
                    $query = $query->where('employee_id', $request->employee_id);
                }
                if ($request->has('job_id')) {
                    $query = $query->where('job_id', $request->job_id);
                }
                if ($request->has('date')) {
                    $query = $query->where('date', $request->date);
                }
                if ($request->has('shift_number')){
                    $query = $query->where('shift_number', $request->shift_number);
                }
                return $query->get();
            }
        } else {
            return NULL;
        }
    }

    //Route functions
    function getHours (Request $request) {
        #POST: returns the hours of an employer, filtered by request
        $employer = $this->getEmployerObject();
        if ($employer != NULL) {
            $query = DB::table('hours')->where('employer_id', $employer->employer_id);
            if ($request->has('employee_id')) {
                $query = $query->where('employee_id', $request->employee_id);
            }
            if ($request->has('job_id')) {
                $query = $query->where('job_id', $request->job_id);
            }
            if ($request->has('date')) {
                $query = $query->where('date', $request->date);
            }
            if ($request->has('shift_number')){
                $query = $query->where('shift_number', $request->shift_number);
            }
            return $query->get();
        } else {
            return response()->json(['failed_to_authenticate'], 401);
        }
    }

    function postHours (Request $request) {
        #PRE:   the request must contain each of the following
        #       an employee_id mapping to an employee object
        #       a job_id mapping to a job object
        #       a date in the format YYYY-MM-DD HH:MM:SS (military time)
        #       a shift number as an integer
        #       a quantity as a number of hours OR a start and end time formatted as dates (see above)
        #POST:  creates an hours object of the specified keys
        $employer = $this->getEmployerObject();
        if ($employer != NULL) {
            if ($request->has('employee_id') &&
                $request->has('job_id') &&
                $request->has('date') &&
                $request->has('shift_number') &&
                ($request->has('quantity') || ($request->has('start') && $request->has('end')))) 
            {
                $quantity = -1;
                $job = $this->getJobObject ($request->job_id);
                if($request->has('quantity')) {
                    $quantity = $request->quantity;
                } else {
                    if($job->UOM == "Dollars"){
                        return response()->json(['invalid UOM'], 400);
                    } else if ($job->UOM == "Hourly") {
                        $date1 = new \DateTime($request->start);
                        $date2 = new \DateTime($request->end);
                        $interval = $date1->diff($date2);
                        $quantity = ($interval->s / 60) + 
                                    ($interval->h) +
                                    ($interval->d * 24);
                    } else {
                        return response()->json(['bad UOM'], 400);
                    }
                }
                if ($quantity < 0) {
                    return response()->json(['negative quantity'], 400);
                }
                DB::table('hours')->insert([
                    'employer_id' => $employer->employer_id,
                    'employee_id' => $request->employee_id,
                    'job_id' => $request->job_id,
                    'date' => $request->date,
                    'shift_number' => $request->shift_number,
                    'quantity' => $quantity,
                    'start' => $request->input('start', NULL),
                    'end' => $request->input('end', NULL),
                    'entry_clerk_emp_ID' => $this->getUserObject()->employer_id,
                    'entry_clerk' => $this->getUserObject()->username
                ]);
                return response()->json(['hours submitted'], 201);
            } else {
                return response()->json(['missing arguments'], 401);
            }
        } else {
            return response()->json(['failed_to_authenticate'], 401);
        }
    }
    
    function updateHours (Request $request) {
        #POST:  updates the specified hours object
        $employer = $this->getEmployerObject();
        if ($employer != NULL) {
            $hours = $this->getHoursObject($request, $onlyOne = TRUE);
            if($hours != NULL){
                $quantity = $hours->quantity;
                $job = $this->getJobObject ($request->job_id);
                if($job->UOM == "Dollars"){
                    return response()->json(['invalid UOM'], 400);
                } else if ($job->UOM == "Hourly") {
                    $date1 = new \DateTime($request->start);
                    $date2 = new \DateTime($request->end);
                    $interval = $date1->diff($date2);
                    $quantity = ($interval->s / 60) + 
                                ($interval->h) +
                                ($interval->d * 24);
                }
                if ($quantity < 0) {
                    return response()->json(['negative quantity'], 400);
                }
                $start = $hours->start;
                $end = $hours->end;
                if ($request->has('start') && $request->has('end')){
                    $start = $request->start;
                    $end = $request->end;
                }
                DB::table('hours')->update([
                    'quantity' => $quantity,
                    'start' => $start,
                    'end' => $end,
                    'entry_clerk_emp_ID' => $this->getUserObject()->employer_id,
                    'entry_clerk' => $this->getUserObject()->username
                ]);
                return response()->json(['hours updated'], 204);
            } else {
                return response()->json(['object not found'], 404);
            }
        } else {
            return response()->json(['failed_to_authenticate'], 401);
        }
    }

    function deleteHours (Request $request){
        #POST: deletes the hours
        $employer = $this->getEmployerObject();
        if ($employer != NULL) {
            $hours = $this->getHoursObject($request, $onlyOne = TRUE);
            if($hours != NULL) {
                $query = DB::table('hours')->where('employer_id', $hours->employer_id);
                $query = $query->where('employee_id', $hours->employee_id);
                $query = $query->where('job_id', $hours->job_id);
                $query = $query->where('date', $hours->date);
                $query = $query->where('shift_number', $hours->shift_number);
                DB::table('hours')->delete();
            } else {
                return response()->json(['missing arguments'], 401);
            }
        } else {
            return response()->json(['failed_to_authenticate'], 401);
        }
    }
}
