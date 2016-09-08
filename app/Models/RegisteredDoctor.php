<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class RegisteredDoctor extends FBModel{

    public $connection = 'firebird_cabinet';
    public $table = 'REGISTEREDDOCTOR';
    public $timestamps = false;

    public static function findBySaloonId($saloonId){

        $connection = with(new static)->connection;
        $tableName  = with(new static)->getTable();
        $doctors = DB::connection($connection)->select("select * from $tableName where saloonid = $saloonId");
        return $doctors[0];
    }
}