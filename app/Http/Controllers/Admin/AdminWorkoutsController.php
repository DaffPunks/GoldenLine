<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Common\BasicScheduleController;

use App\Services\Decoder;
use App\Services\Checker;
use App\Services\Cleaner;

use Carbon\Carbon;

use Illuminate\Support\Facades\Input;
use Request;

use App\Models\Workout;
use App\Models\WorkoutEntry;
use App\Models\WorkoutGroup;
use App\Models\UnconfirmedWorkoutEntry;
use App\User;

class AdminWorkoutsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function canCreateWorkout($dateStart, $dateEnd, $gymType)
    {
        $basicScheduleController = new BasicScheduleController();
        $workoutsForThisTime = $basicScheduleController->getWorkoutsForTime($dateStart, $dateEnd, $gymType);

        error_log(count($workoutsForThisTime));

        if (count($workoutsForThisTime) > 0)
        {
            return false;
        }

        return true;
    }

    public function createWorkout(){

        if(Request::ajax())
        {
            $eventDateFormat = 'Y.m.d H:i';

            $data = Input::all();

            $coachId = (int)$data['coach_id'];
            $gymType = $data['gym_type'];
            $dateStart = Carbon::createFromFormat($eventDateFormat, $data['date_start']);
            $dateEnd = Carbon::createFromFormat($eventDateFormat, $data['date_end']);
            $workoutName = Cleaner::cleanString($data['workout_name']);
            $isPersonal = !strcmp((string)$data['is_personal'],'true');
            $placeCount = (int)$data['places_count'];
            $weeksAmount = (int)$data['weeks_amount'];

            if(Checker::isInteger($coachId) && $coachId > 0 &&
                BasicScheduleController::allowedGymName($gymType) &&
                Checker::isDate($dateStart) &&
                Checker::isDate($dateEnd) &&
                Checker::isString($workoutName) &&
                Checker::isBool($isPersonal) &&
                Checker::isInteger($placeCount) &&
                Checker::isInteger($weeksAmount)){

                if(!$this->canCreateWorkout($dateStart, $dateEnd, $gymType)){
                    return [
                        'status' => 'fail',
                        'msg' => 'Это время уже занято'
                    ];
                }

                $dateStart->subWeek();
                $dateEnd->subWeek();
                $groupId = WorkoutGroup::getNextID();

                $workoutNameIdError = null;
                $workoutNameId = AdminWorkoutNamesController::getWorkoutNameIdFromWorkoutName(Decoder::encodeString($workoutName), $workoutNameIdError);
                if(isset($workoutNameIdError)){
                    return [
                        'status' => 'fail',
                        'msg' => 'Сервер не может сохранить данное имя тренеровки'
                    ];
                }

                for($i = 0; $i < $weeksAmount; ++$i){

                    $workout = new Workout();
                    $workout->COACHID = $coachId;
                    $workout->WORKOUTNAMEID = $workoutNameId;
                    $workout->CLIENTSCAPACITY = $placeCount;
                    $workout->GYMTYPE = $gymType;
                    $workout->START_EVENT = $dateStart->addWeeks(1);
                    $workout->END_EVENT = $dateEnd->addWeeks(1);
                    $workout->ISPERSONAL = $isPersonal;
                    $workout->ISCOACHREPLACED = false;
                    $workout->ISACTIVE = true;

                    if(!($workout->save())){
                        return ['status' => 'fail'];
                    }

                    $workouts = Workout::select(" FIRST 1 ID "," ORDER BY ID DESC ");

                    $workoutGroup = new WorkoutGroup();
                    $workoutGroup->GROUPID = $groupId;
                    $workoutGroup->WORKOUTID = $workouts[0]->ID;


                    if(! ($workoutGroup->save())){
                        return ['status' => 'fail'];
                    }
                }

                return ['status' => 'success'];
            }
            else{
                return [
                    'status' => 'fail'
                ];
            }
        }
    }


    public function updateWorkout(){

        if (Request::ajax()) {

            $data = Input::all();

            $updateSubsequent = !strcmp((string)$data['update_subsequent'], 'true');

            $isCoachReplaced = !strcmp((string)$data['is_coach_replaced'],'true');

            $workoutId = (int)$data['workout_id'];
            $workoutName = Cleaner::cleanString($data['workout_name']);
            $workoutStartEvent = $data['start_event'];
            $coachId = (int)$data['coach_id'];
            $realcoachId = (int)$data['realcoach_id'];

            $subscriptionObject = isset($data['subscriptions']) ? $data['subscriptions'] : new \stdClass();
            $unconfirmedClientObjects = isset($data['unconfirmed_clients']) ? $data['unconfirmed_clients'] : new \stdClass();

            if(Checker::isBool($updateSubsequent) &&
                Checker::isBool($isCoachReplaced) &&
                Checker::isInteger($workoutId) &&
                Checker::isString($workoutName) &&
                Checker::isDate($workoutStartEvent) &&
                Checker::isInteger($coachId) &&
                Checker::isInteger($realcoachId)
            ){

                $isCoachReplaced = (int)$isCoachReplaced;

                if(! $isCoachReplaced) {
                    $realcoachId = null;
                }

                $workoutNameIdError = null;
                $workoutNameId = AdminWorkoutNamesController::getWorkoutNameIdFromWorkoutName(Decoder::encodeString($workoutName), $workoutNameIdError);
                if(isset($workoutNameIdError)){
                    return [
                        'status' => 'fail',
                        'msg' => 'Сервер не может сохранить данное имя тренеровки'
                    ];
                }

                foreach ($subscriptionObject as $subscriptionId => $actionType) {
                    if($actionType == 0){
                        WorkoutEntry::del("where workoutid = $workoutId AND subscriptionid = $subscriptionId");
                    }else if($actionType == 2){
                        $newEntry = new WorkoutEntry();
                        $newEntry->WORKOUTID = $workoutId;
                        $newEntry->SUBSCRIPTIONID = $subscriptionId;
                        if(!($newEntry->save())){
                            return ['status' => 'fail'];
                        }
                    }
                }

                foreach ($unconfirmedClientObjects as $unconfirmedClientId => $actionType) {
                    if($actionType == 0){
                        UnconfirmedWorkoutEntry::del("where workoutid = $workoutId AND clientid = $unconfirmedClientId");
                    }
                }

                $workouts = [];

                if($updateSubsequent == 'true'){
                    error_log('updateSubsequent');
                    $workouts = WorkoutGroup::getSubsequentWorkouts($workoutId, $workoutStartEvent);
                }
                else{
                    $workouts[] = Workout::find($workoutId);
                }

                //Update all subsequent workouts
                $length = count($workouts);
                error_log($length);
                if($length > 0) {

                    $query = ' where ';
                    $i = 0;
                    foreach ($workouts as $workout) {
                        $id = $workout->ID;

                        if ($i < $length - 1) {
                            $query .= " id=$id or ";
                        } else if ($i == $length - 1) {
                            $query .= " id=$id ";
                        }

                        $i++;
                    }

                    Workout::upd("set WORKOUTNAMEID = $workoutNameId, COACHID = $coachId, REALCOACHID = $realcoachId, ISCOACHREPLACED = $isCoachReplaced", $query);
                }

                return [
                    'status' => 'success'
                ];
            }
            else{
                return [
                    'status' => 'fail'
                ];
            }
        }
    }

    public function deleteWorkout(){

        if(Request::ajax()) {

            $data = Input::all();

            $deleteSubsequent = !strcmp((string)$data['update_subsequent'], 'true');;
            $workoutId = (int)$data['workout_id'];
            $workoutStartEvent = $data['start_event'];

            if(Checker::isBool($deleteSubsequent) &&
                Checker::isInteger($workoutId) &&
                Checker::isDate($workoutStartEvent)){

                $workouts = [];

                error_log((string)$deleteSubsequent);

                if($deleteSubsequent == 'true'){
                    error_log('deleteSubsequent');
                    $workouts = WorkoutGroup::getSubsequentWorkouts($workoutId, $workoutStartEvent);
                }
                else{
                    $workouts[] = Workout::find($workoutId);
                }

                //Update all subsequent workouts
                $length = count($workouts);
                error_log($length);
                if($length > 0) {

                    $query = ' where ';
                    $i = 0;
                    foreach ($workouts as $workout) {
                        $id = $workout->ID;

                        if ($i < $length - 1) {
                            $query .= " id=$id or ";
                        } else if ($i == $length - 1) {
                            $query .= " id=$id ";
                        }

                        $i++;
                    }

                    Workout::upd("set ISACTIVE = 0", $query);
                }

                return [
                    'status' => 'success'
                ];
            }
            else{
                return [
                    'status' => 'fail'
                ];
            }
        }
    }
}