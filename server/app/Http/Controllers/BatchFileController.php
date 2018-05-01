<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BatchFileController extends AuthenticatedController
{
    //Properties
    private $url = "set/your/url/here";

    //Route functions
    public function processFile (Request $request) {
        #PRE: the request MUST contain a JSON of the specified format
        #     see the documentation for details
        #POST: processes the files, inserting employers, employees, and jobs where they do not exist

        //Checking user
        $user = $this->getUserObject();
        if($user == NULL || $user->administrator == FALSE){
            return response()->json(['failed to authenticate'], 401);
        }

        //Parsing JSON
        $jsonObject = json_decode($request->getContent());
        if ($jsonObject == FALSE || $jsonObject == NULL){
            return response()->json(['Bad format'], 400);
        }
        
        //Adding each employer
        for ($i = 0; $i < sizeof($jsonObject); $i++){
            $employer = DB::table('employer')->where('employer_id', $jsonObject[$i]->employer_id)->first();
            if ($employer == NULL) {
                $employer = DB::table('employer')->insert(
                    [
                        'employer_id' => $jsonObject[$i]->employer_id,
                        'name' => $jsonObject[$i]->name,
                        'address_line_1' => $jsonObject[$i]->address_line_1,
                        'address_line_2' => $jsonObject[$i]->address_line_2,
                        'city' => $jsonObject[$i]->city,
                        'state' => $jsonObject[$i]->state,
                        'zip' => $jsonObject[$i]->zip
                    ]
                );
                $employer = DB::table('employer')->where('employer_id', $jsonObject[$i]->employer_id)->first();
            }

            // Adding each employee
            for($j = 0; $j < sizeof($jsonObject[$i]->employees); $j++){
                $jsonEmployee = $jsonObject->employees[$j];
                $employee = DB::table('employee')->where('employee_id', $jsonObject[$i]->employee_id)->first();
                if($employee == NULL){
                    DB::table('employee')->insert(
                        [
                            'employee_id' => $jsonEmployee->employee_id,
                            'employer_id' => $employer->employer_id,
                            'employer_employee_id' => $jsonEmployee->employer_employee_ID,
                            'firstname' => $jsonEmployee->firstname,
                            'middlename' => $jsonEmployee->middlename,
                            'lastname' => $jsonEmployee->lastname,
                            'address_line_1' => $jsonEmployee->address_line_1,
                            'address_line_2' => $jsonEmployee->address_line_2,
                            'city' => $jsonEmployee->city,
                            'state' => $jsonEmployee->state,
                            'zip' => $jsonEmployee->zip,
                            'active' => $jsonEmployee->active,
                            'hire_date' => $jsonEmployee->hire_date
                        ]
                    );
                }
            }

            //Adding each job
            for($j = 0; $j < sizeof($jsonObject[$i]->jobs); $j++){
                $jsonJob = $jsonObject[$i]->jobs[$j];
                $job = DB::table('job')->where('job_id', $jsonJob->job_id)->first();
                if($job == NULL){
                    $job = DB::table('job')->insert(
                        [
                            'job_id' => $jsonJob->job_id,
                            'employer_id' => $employer->employer_id,
                            'title' => $jsonJob->title,
                            'UOM' => $jsonJob->UOM,
                            'rate' => $jsonJob->rate
                        ]
                    );
                }
                for ($k = 0; $k < sizeof($jsonJob->employees); $k++){
                    DB::table('employee_job')->insert([
                        'job_id' => $jsonJob->job_id,
                        'employee_id' => $jsonJob->employees[$k]->employee_id
                    ]);
                }
            }

            //Adding each user
            for($j = 0; $j < sizeof($jsonObject[$i]->users); $j++){
                $jsonUser = $jsonObject[$i]->users[$j];
                $user = DB::table('users')->where('user_id', $jsonUser->user_id)->first();
                if($user == NULL){
                    $user = DB::table('users')->insert([
                        'user_id' => $jsonUser->user_id,
                        'employer_id' => $employer->employer_id,
                        'employee_id' => $jsonUser->employee_id,
                        "password" => Hash::make($jsonUser->password),
                        "administrator" => $jsonUser->administrator
                    ]);
                }
            }
        }
    }

    public function sendFile () {
        #POST: sends the file containing our hours to the black box
        
        //Checking user
        $user = $this->getUserObject();
        if($user == NULL || $user->administrator == FALSE){
            return response()->json(['failed to authenticate'], 401);
        }

        //Sending hours
        $hoursArray = DB::table('hours')->where('batch_processed', FALSE)->get();
        $curlRequest = curl_init($url);
        curl_setopt($curlRequest, CURLOPT_POST, 1);
        curl_setopt($curlRequest, CURLOPT_POSTFIELDS, json_encode($hoursArray));
        curl_setopt($curlRequest, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        $response = curl_exec($curlRequest);
        curl_close($curlRequest);
    }
}
