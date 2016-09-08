<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class WorkoutGroup extends FBModel{

    public $connection = 'firebird_cabinet';
    public $table = 'WORKOUTGROUP';
    public $timestamps = false;

    public static function getNextID(){

        $connection = with(new static)->connection;
        $max_group = DB::connection($connection)->select("SELECT FIRST 1 GROUPID FROM WORKOUTGROUP ORDER BY GROUPID DESC");
        if($max_group == []) {
            $max_group_id = 0;
        }else{
            $max_group_id = $max_group[0]->GROUPID;
        }
        return $max_group_id+1;
    }

    public static function getSubsequentWorkouts($workoutId, $workoutStartEvent){

        $connection = with(new static)->connection;
        return DB::connection($connection)->select("SELECT * FROM WORKOUT WHERE ID IN(SELECT WORKOUTID FROM WORKOUTGROUP WHERE GROUPID = (SELECT GROUPID FROM WORKOUTGROUP WHERE WORKOUTID = $workoutId)) AND START_EVENT >= cast('$workoutStartEvent' as timestamp)");
    }
}