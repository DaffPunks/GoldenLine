<?php

namespace App\Models;

//use Illuminate\Support\Facades\DB;

class SubscriptionVisit extends FBModel{

    public $connection = 'firebird_zl';
    public $table = 'SUBSCRIPTIONVISIT';

    public static function visitsCountForSubscriptionId($subscriptionId){
        $visits = self::select('ID', "where subscriptionid = $subscriptionId");
        return count($visits);
    }
}