<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    //Internal storage variables
    protected $table = 'employer';
    protected $primaryKey = 'employer_id';
    protected $timestamps = FALSE;
}
