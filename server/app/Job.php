<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    //Internal storage variables
    protected $table = 'job';
    protected $primaryKey = 'job_id';
    protected $timestamps = FALSE;

    //Personal storage variables
    public $secondaryKeys = ['employer_id'];
}
