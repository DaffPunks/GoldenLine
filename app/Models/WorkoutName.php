<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class WorkoutName extends FBModel
{
    public $connection = 'firebird_cabinet';
    public $timestamps = true;
    public $table = 'WORKOUTNAME';
}