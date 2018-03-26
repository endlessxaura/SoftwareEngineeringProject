<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    //Internal storage variables
    protected $table = 'users';
    protected $primaryKey = 'employer_id';
    public $timestamps = FALSE;

    //Hidden keys
    protected $hidden = [
        'password'
    ];
}
