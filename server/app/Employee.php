<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //Internal storage variables
    protected $table = 'employee_id';
    protected $primaryKey = 'employer_id';
    protected $timestamps = FALSE;
}
