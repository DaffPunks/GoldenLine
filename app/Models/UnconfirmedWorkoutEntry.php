<?php

namespace App\Models;

class UnconfirmedWorkoutEntry extends FBModel{

    public $connection = 'firebird_cabinet';
    public $table = 'UNCONFIRMEDWORKOUTENTRY';
    public $timestamps = false;
}