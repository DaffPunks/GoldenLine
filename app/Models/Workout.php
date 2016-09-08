<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Workout extends FBModel{

    public $connection = 'firebird_cabinet';
    public $table = 'WORKOUT';
    public $timestamps = false;
    protected $fillable = ['COACHID', 'WORKOUTNAMEID', 'CLIENTSCAPACITY', 'GYMTYPE', 'START_EVENT', 'END_EVENT', 'ISACTIVE'];

    public static function isValidWorkout($rawWorkout){

        if(Carbon::parse($rawWorkout->START_EVENT)->gt(Carbon::now()) //If start event date greater then now
            && $rawWorkout->ISACTIVE == 1) return true;

        return false;
    }

    public static function deactivateWithId($workoutId){
        $connection = with(new static)->connection;
        $tableName = with(new static)->getTable();
        DB::connection($connection)->update("update $tableName set isactive=0 where id = $workoutId");
    }
}