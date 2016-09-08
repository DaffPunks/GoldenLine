<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Services\Decoder;
use App\Services\Checker;
use App\Services\Cleaner;

use Illuminate\Support\Facades\Input;
use Request;

use App\Models\WorkoutName;

class AdminWorkoutNamesController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function formatWorkoutNames($rawWorkoutNames){

        $newWorkoutNames = [];

        foreach($rawWorkoutNames as $workoutName){

            $element = [];
            $element["id"] = $workoutName->ID;

            $element['name'] = Decoder::decodeName($workoutName->NAME);

            if($workoutName->ISACTIVE){
                $newWorkoutNames[] = $element;
            }
        }
        return $newWorkoutNames;
    }

    public function index()
    {
        $adminId = Auth::user()->employeeid;
        return MainAdminController::renderViewWithAdminInfo('admin.workoutNames', $adminId, [
            'tab' => 'sport',
            'title' => 'Тренеровки']);
    }

    public function search(){

        if(Request::ajax())
        {
            $data = Input::all();

            $searchQueryUTF8 = Cleaner::cleanString(mb_strtolower($data['search'],'UTF-8'));

            if(Checker::isString($searchQueryUTF8)){

                $searchQueryUTF8 = str_replace("-", "", $searchQueryUTF8);
                $searchQuery = Decoder::encodeString($searchQueryUTF8);

                $workoutNames = WorkoutName::select("*", " where (lower(cast(NAME as varchar(255) character set WIN1251)) like '%$searchQuery%') AND ISACTIVE = 1");//DB::connection('firebird_cabinet')->select($query);
                $formattedWorkoutNames = $this->formatWorkoutNames($workoutNames);

                return $formattedWorkoutNames;
            }
            else{
                return [];
            }
        }
    }

    public static function deleteWorkoutName() {

        if(Request::ajax())
        {
            $data = Input::all();
            $workoutName = Cleaner::cleanString($data['workout_name']);

            $workoutName = Decoder::encodeString($workoutName);

            WorkoutName::upd('SET ISACTIVE = 0', "WHERE NAME = '$workoutName'");

            return [
                'status' => 'success'
            ];
        }
    }

    public static function getWorkoutNameIdFromWorkoutName($workoutName, &$error){

        $workoutNames = WorkoutName::where("NAME = '$workoutName'");

        if(count($workoutNames) > 0){
            $workoutName = $workoutNames[0];

            if(!$workoutName->ISACTIVE){
                $workoutName->ISACTIVE = true;
                if(!$workoutName->save()){
                    $error = "Failed to set Workout Name Active";
                    return null;
                }
            }

            return $workoutName->ID;
        }
        else{
            $newWorkoutName = new WorkoutName();
            $newWorkoutName->NAME = $workoutName;
            $newWorkoutName->ISACTIVE = true;

            if(!$newWorkoutName->save()){
                $error = "Failed to save new Workout Name";
                return null;
            }

            $workoutNames = WorkoutName::select("FIRST 1 ID", "ORDER BY ID DESC");
            return $workoutNames[0]->ID;
        }
    }
}
