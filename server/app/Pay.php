<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pay extends Model
{
    //Internal storage variables
    protected $table = 'pays';
    protected $primaryKey = 'employer_id';
    protected $timestamps = FALSE;
}