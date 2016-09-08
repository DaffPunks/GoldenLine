<?php

namespace App\Models;

class Coach extends FBModel{

    public $connection = 'firebird_cabinet';
    public $timestamps = true;
    public $table = 'COACH';
}