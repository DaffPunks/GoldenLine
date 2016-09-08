<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use App\Models\Workout;
use App\Models\WorkoutEntry;
use App\Models\UnconfirmedWorkoutEntry;
use App\Models\Coach;
use App\Models\Subscription;

use Illuminate\Support\Facades\Input;
use Request;
use Illuminate\Http\Response;

use App\Services\Checker;

class BasicScheduleController extends Controller {

    public $periodInDays = 7;

    public static $gymNames = [
        'big' => 'Большой зал',
        'small' => 'Малый зал',
//            'fitness' => 'Тренажерный зал'
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public static function allowedGymName($gymName){

        foreach(array_keys(self::$gymNames) as $gymNamesKeys){

            if($gymNamesKeys == $gymName){
                return true;
            }

        }
    }

    public function getPeriodData($daysOffset){

        $monthNow = trans('date.' . (Carbon::now()->modify("$daysOffset days")->format('F')));
        $monthFuture = trans('date.' . Carbon::now()->modify("$daysOffset days")->addDays($this->periodInDays-1)->format('F'));

        $periodData = [
            'monthNow' => $monthNow == $monthFuture ? '' : $monthNow,
            'monthFuture' => $monthFuture,
            'dayNow' => Carbon::now()->modify("$daysOffset days")->day,
            'dayFuture' => Carbon::now()->modify("$daysOffset days")->addDays($this->periodInDays-1)->day
        ];
        return $periodData;
    }

    public function getTableHead($daysOffset){

        $beginning = Carbon::now()->modify($daysOffset . ' days');

        $tableHead = [];

        for($i = 0; $i < $this->periodInDays; $i++){

            $day = [];
            $day['day'] = trans('date.' . $beginning->format('D'));//Translator::getShortDay($today->dayOfWeek);
            $day['date'] = $beginning->day;

            $beginning->addDays(1);

            $tableHead[$i] = $day;
        }

        return $tableHead;
    }

    public function getWorkoutsForTime($from, $to, $gymtype) {

        if(Checker::isDate($from) &&
            Checker::isDate($to) &&
            self::allowedGymName($gymtype)){

            $fromSQL = "cast('" . $from . "' as timestamp)";
            $toSQL = "cast('" . $to . "' as timestamp)";

            $query = "((START_EVENT >=" . $fromSQL . " AND " . "START_EVENT <" . $toSQL . ")" . " OR " .
                "(END_EVENT >" . $fromSQL . " AND " . "END_EVENT <=" . $toSQL . "))" . " AND ";

            $query .=
                "GYMTYPE = '$gymtype' AND
                 ISACTIVE = 1
                 order by START_EVENT desc";

            error_log($query);
            $workouts = Workout::where($query);

            return $workouts;
        }
        else{
            return [];
        }
    }

    public function getWorkouts($gymtype, $daysOffset, $periodSinceToday = null, $noPersonal = true){

        if(self::allowedGymName($gymtype) &&
            Checker::isInteger($daysOffset) &&
            Checker::isDate($periodSinceToday)){

            $weekLater = Carbon::today()->modify("$daysOffset days")->addDays($this->periodInDays);

            $query = "START_EVENT >= cast('" . Carbon::today()->modify("$daysOffset days")->toDateTimeString() . "' as timestamp) AND
                 END_EVENT <=  cast('" . $weekLater->toDateTimeString() . "' as timestamp) ";

            if(isset($periodSinceToday)){
                $query .= " AND END_EVENT <=  cast('" . $periodSinceToday->toDateTimeString() . "' as timestamp) ";
            }

            if($noPersonal){
                $query .= " and ISPERSONAL <> 1 ";
            }

            $query .=
                "AND GYMTYPE = '$gymtype' AND ISACTIVE = 1
                 order by START_EVENT desc";
            $workouts = Workout::where($query); //

            error_log(count($workouts));
            return $workouts;
        }else{
            return [];
        }
    }

    public function countFreePlacesForWorkout($rawWorkout){

        $capacity = $rawWorkout->CLIENTSCAPACITY;
        $clientEntries = WorkoutEntry::where("WORKOUTID = $rawWorkout->ID");
        $clientUnconfirmedEntries = UnconfirmedWorkoutEntry::where("WORKOUTID = $rawWorkout->ID");
        $entryCount = count($clientEntries)+count($clientUnconfirmedEntries);

        return $capacity-$entryCount;
    }

    public function getSubscriptionsForClientId($clientId){

        return Subscription::activeSubscriptionWhere("CLIENTID = $clientId");
    }

    public function doesWorkoutEntryExists($subscriptionId, $workoutId){

        $entries = WorkoutEntry::where("WORKOUTID = " . $workoutId . " and SubscriptionID = " . $subscriptionId);
        if(count($entries) > 0)
            return true;

        return false;
    }

    public function addSubscriptionToWorkout($subscription, $workout, &$errorMsg){

        if(Workout::isValidWorkout($workout) &&
            !$this->hasClientIdAnEntry($workout, $subscription->CLIENTID) &&
            Subscription::isValidSubscription($subscription)){

            $freePlacesCount = $this->countFreePlacesForWorkout($workout);
            $alreadyExists = $this->doesWorkoutEntryExists($subscription->ID, $workout->ID);

            if($freePlacesCount > 0 && !$alreadyExists){

                $newWorkoutEntry = new WorkoutEntry();

                if($newWorkoutEntry->save()){

                    $errorMsg = null;
                    return true;
                }
                else{

                    $errorMsg = 'Произошла неизвестная ошибка';
                    return false;

                }
            }
            else if($freePlacesCount == 0){

                $errorMsg = 'Не осталось свободных мест';
                return false;
            }
            else if($alreadyExists){

                $errorMsg = 'Вы уже записаны';
                return false;
            }
        }
    }
}
