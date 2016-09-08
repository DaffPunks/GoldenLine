<?php

namespace App\Models;

class WorkoutEntry extends FBModel{

    public $connection = 'firebird_cabinet';
    public $table = 'WORKOUTENTRY';
    public $timestamps = false;
}