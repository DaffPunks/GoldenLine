<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Subscription extends FBModel{

    public $connection = 'firebird_zl';
    public $table = 'SUBSCRIPTION';

    public static function isValidSubscription($rawSubscription){

        if($rawSubscription->SUBSCRIPTIONSTATUSID == 2)
            return true;

        return false;
    }

    public static function activeSubscriptionWhere($rawStatement){

        $tableName = with(new static)->getTable();
        $query =  "select ID, DICTSUBSCRIPTIONID, NUMBER, SUBSCRIPTIONSTATUSID from $tableName where SUBSCRIPTIONSTATUSID = 2 ";
        if(isset($rawStatement)){
            $query = $query . " and $rawStatement ";
        }
        return DB::connection(with(new static)->connection)->select($query);
    }
}