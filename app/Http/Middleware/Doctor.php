<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Doctor extends Role{
    protected $col_name = 'doctorid';
}