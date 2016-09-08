<?php

namespace App\Http\Controllers\Admin;

use App\Services\Checker;
use Log;

use App\Http\Controllers\Common\BasicScheduleController;
use App\Models\Client;
use App\Models\Subscription;
use App\Models\DictSubscription;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\Workout;
use App\Models\WorkoutGroup;
use App\Models\WorkoutEntry;
use App\Models\WorkoutName;
use App\Models\Coach;
use App\Models\UnconfirmedWorkoutEntry;

use Illuminate\Support\Facades\Input;
use Request;
use Illuminate\Http\Response;

use App\Services\Decoder;

use App\Http\Controllers\Admin\AdminWorkoutNamesController;

class AdminScheduleController extends BasicScheduleController {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function formatWorkouts($rawWorkouts, $daysOffset){

        $beginning = Carbon::today()->modify($daysOffset . ' days');
        $newWorkouts = [];

        foreach($rawWorkouts as $workout){

            $freePlacesCount = $this->countFreePlacesForWorkout($workout);

            $coach = Coach::find($workout->COACHID);

            $newWorkout = [];
            $newWorkout["id"] = $workout->ID;
            $newWorkout["coachName"] = Decoder::decodeName("$coach->SURNAME $coach->NAME");
            $newWorkout["coachid"] = $workout->COACHID;
            $newWorkout["isCoachReplaced"] = $workout->ISCOACHREPLACED;

            $workoutName = WorkoutName::find($workout->WORKOUTNAMEID);

            $newWorkout['name'] = Decoder::decodeName($workoutName->NAME);
            $newWorkout['clientscapacity'] = $workout->CLIENTSCAPACITY;
            $newWorkout['freeplaces'] = $freePlacesCount;

            $startDate = Carbon::parse($workout->START_EVENT);
            $x = $startDate->diff($beginning)->days;
            $y = number_format($startDate->format('H')+$startDate->format('i')/60,2);

            $newWorkout["date"] = $startDate->format('Y.m.d');
            $newWorkout["starttime"] = $startDate->format('H:i');
            $newWorkout["endtime"] = Carbon::parse($workout->END_EVENT)->format('H:i');
            $newWorkout["startevent"] = $workout->START_EVENT;

            $newWorkouts[$x][$y] = $newWorkout;
            $newWorkouts[$x][number_format($y+0.25,2)] = '0';
            $newWorkouts[$x][number_format($y+0.5,2)] = '0';
            $newWorkouts[$x][number_format($y+0.75,2)] = '0';
        }
        return $newWorkouts;
    }

    public function index($gymtype, $daysOffset = 0)
    {
        if(self::allowedGymName($gymtype)){

            $workouts = $this->getWorkouts($gymtype, (int)$daysOffset, null, false);

            return MainAdminController::renderViewWithAdminInfo('admin.sport.schedule', [
                'tab'=>'sport',
                'title' => self::$gymNames[$gymtype],
                'period' => $this->getPeriodData($daysOffset),
                'tableHead' => $this->getTableHead($daysOffset),
                'workouts' => $this->formatWorkouts($workouts, $daysOffset),
                'gymtype' => $gymtype,
                'gymnames' => self::$gymNames
            ]);
        }
        else{
            return '';
        }
    }

    public function getUnconfirmedClientsForWorkout($workoutId)
    {
        $desiredWorkout = Workout::find($workoutId);

        if(isset($desiredWorkout)){

            $responseData = [];

            $unconfirmedWorkoutEntries = UnconfirmedWorkoutEntry::where("workoutid = " . $workoutId);

            foreach ($unconfirmedWorkoutEntries as $entry) {

                $element = [];
                $client = Client::find($entry->CLIENTID);

                $element['client_id'] = $client->ID;
                $element['client_name'] = Decoder::decodeName("$client->SURNAME $client->NAME");
                $element['client_phone'] = Decoder::decodeName($client->CELLPHONE);
                $responseData[] = $element;
            }

            return $responseData;
        }
    }

    public function getIsCoachReplacedForWokout($workoutId){
        $workout = Workout::find($workoutId);
        return $workout->ISCOACHREPLACED;
    }

    public function getEntryData(){

        if (Request::ajax()) {

            $data = Input::all();

            $workoutId = (int)$data['workout_id'];

            if(Checker::isInteger($workoutId)){

                $responseData = [];
                $responseData["clients_and_subscriptions"] = $this->getClientsAndSubscriptionsForWorkout($workoutId);
                $responseData["unconfirmed_clients"] = $this->getUnconfirmedClientsForWorkout($workoutId);
                $responseData["is_coach_replaced"] = $this->getIsCoachReplacedForWokout($workoutId);

                return $responseData;
            }
            else{
                return [];
            }
        }
    }

    //Subscriptions
    public function getSubscriptionsForWorkout($workoutId){

        $workoutEntries = WorkoutEntry::where('workoutid = ' . $workoutId);
        $length = count($workoutEntries);

        if($length > 0){

            $query = 'where ';
            $i = 0;
            foreach($workoutEntries as $workoutEntry){

                $subscriptionId = $workoutEntry->SUBSCRIPTIONID;

                if ($i < $length - 1){
                    $query .= " id=$subscriptionId or ";
                }
                else if ($i == $length - 1) {
                    $query .= " id=$subscriptionId ";
                }

                $i++;
            }
            $subscriptions = Subscription::select('ID, DICTSUBSCRIPTIONID, NUMBER, CLIENTID', $query);

            return $subscriptions;
        }
        else{
            return [];
        }
    }

    public function getClientsForSubscriptions($subscriptions){

        $length = count($subscriptions);
        if($length > 0) {

            $query = ' where ';
            $i = 0;
            foreach ($subscriptions as $subscription) {
                $clientId = $subscription->CLIENTID;

                if ($i < $length - 1) {
                    $query .= " id=$clientId or ";
                } else if ($i == $length - 1) {
                    $query .= " id=$clientId ";
                }

                $i++;
            }

            $clients = Client::select('ID, NAME, SURNAME', $query);

            return $clients;
        }
        else{
            return [];
        }
    }

    public function getClientsAndSubscriptionsForWorkout($workoutId)
    {
        $desiredWorkout = Workout::find($workoutId);

        if(isset($desiredWorkout)){

            $subscriptions = $this->getSubscriptionsForWorkout($workoutId);
            $length = count($subscriptions);
            if($length > 0){

                $query = ' where ';
                $i = 0;
                foreach ($subscriptions as $subscription) {
                    $dictSubscriptionId = $subscription->DICTSUBSCRIPTIONID;

                    if ($i < $length - 1){
                        $query .= " id=$dictSubscriptionId or ";
                    }
                    else if ($i == $length - 1) {
                        $query .= " id=$dictSubscriptionId ";
                    }

                    $i++;
                }

                $dictSubscriptions = DictSubscription::select(" ID, NAME ", $query);

                $clients = $this->getClientsForSubscriptions($subscriptions);

                $responseData = [];

                foreach ($subscriptions as $subscription) {
                    foreach ($clients as $client){
                        if($subscription->CLIENTID == $client->ID){
                            foreach($dictSubscriptions as $dictSubscription){

                                if($dictSubscription->ID == $subscription->DICTSUBSCRIPTIONID){
                                    $element =[];

                                    $element['id'] = $subscription->ID;
                                    $element['client_name'] = Decoder::decodeName("$client->SURNAME $client->NAME");
                                    $element['number'] = $subscription->NUMBER;
                                    $element['subscription_name'] = Decoder::decodeName($dictSubscription->NAME);

                                    error_log($element['subscription_name']);

                                    $responseData[] = $element;
                                    break;
                                }
                            }
                            break;
                        }
                    }
                }

                return $responseData;
            }
            else{
                return [];
            }
        }
    }

    public function searchSubscriptionsForWorkout(){

        if (Request::ajax()) {

            $data = Input::all();

            $subscriptionNumber = (int)$data['search'];
            $clientId = (int)$data['client_id'];
            $workoutId = (int)$data['workout_id'];

            if(Checker::isInteger($subscriptionNumber) &&
                Checker::isInteger($clientId) &&
                Checker::isInteger($workoutId)){

                $amount = 10;

                $query = " where SUBSCRIPTIONSTATUSID = 2 ";
                if($clientId > 0){
                    $query .= " AND CLIENTID = $clientId ";
                }
                if(isset($data['search']) && $data['search'] != ''){
                    $query .= " AND NUMBER containing '$subscriptionNumber' ";
                }
                $enteredSubscriptions = $this->getSubscriptionsForWorkout($workoutId);
                if(count($enteredSubscriptions) > 0) {
                    foreach ($enteredSubscriptions as $enteredSubscription) {
                        $query = $query . ' AND ID != ' . $enteredSubscription->ID;
                    }
                }

                $subscriptions = Subscription::select("first $amount ID, DICTSUBSCRIPTIONID, NUMBER", $query);
                $length = count($subscriptions);
                if($length > 0){

                    $query = ' where ';
                    $i = 0;
                    foreach ($subscriptions as $subscription) {
                        $dictSubscriptionId = $subscription->DICTSUBSCRIPTIONID;

                        if ($i < $length - 1){
                            $query .= " id=$dictSubscriptionId or ";
                        }
                        else if ($i == $length - 1) {
                            $query .= " id=$dictSubscriptionId ";
                        }

                        $i++;
                    }

                    $dictSubscriptions = DictSubscription::select(" ID, NAME ", $query);

                    $formattedSubscriptions = [];

                    foreach ($subscriptions as $subscription) {
                        foreach($dictSubscriptions as $dictSubscription){

                            if($dictSubscription->ID == $subscription->DICTSUBSCRIPTIONID){
                                $formattedSubscription = [];

                                $formattedSubscription['id'] = $subscription->ID;
                                $formattedSubscription['number'] = $subscription->NUMBER;
                                $formattedSubscription['name'] = Decoder::decodeName($dictSubscription->NAME);

                                $formattedSubscriptions[] = $formattedSubscription;
                                break;
                            }
                        }
                    }

                    return $formattedSubscriptions;
                }
                else{
                    return [];
                }
            }
        }
    }
}
