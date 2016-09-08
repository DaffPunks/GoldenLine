<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class RegisteredClient extends FBModel{

    public $connection = 'firebird_cabinet';
    public $table = 'REGISTEREDCLIENT';
    public $timestamps = false;

    public static function findBySaloonId($saloonId){

        $connection = with(new static)->connection;
        $tableName  = with(new static)->getTable();
        $clients = DB::connection($connection)->select("select * from $tableName where saloonid = $saloonId");
        return $clients[0];
    }
}