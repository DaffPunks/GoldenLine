<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Common\BasicScheduleController;

use App\Models\WorkoutName;
use App\Services\Checker;
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

use App\Services\Decoder;

class ScheduleController extends BasicScheduleController {

	public $limitPeriodSinceToday;

	public function __construct()
	{
		$this->middleware('auth');

		$this->limitPeriodSinceToday = Carbon::today()->modify("14 days");
	}

	public function hasClientSubscriptionAnEntry($workout, $clientId){

		$validClientSubscriptions = Subscription::activeSubscriptionWhere('CLIENTID = ' . $clientId);

		$workoutEntries = WorkoutEntry::where('WORKOUTID = ' . $workout->ID);

		foreach($workoutEntries as $entry){

			foreach($validClientSubscriptions as $subscription){

				error_log($entry->SUBSCRIPTIONID);

				if($entry->SUBSCRIPTIONID == $subscription->ID){
					return true;
				}
			}
		}
		return false;
	}

	public function hasClientAnEntry($workout, $clientId){

		$unconfirmedWorkoutEntries = UnconfirmedWorkoutEntry::where("ClientId = $clientId and WorkoutId = $workout->ID");
		if(count($unconfirmedWorkoutEntries) > 0){
			return true;
		}
		return false;
	}

	public function canEnter($workout){

		if(Carbon::parse($workout->START_EVENT)->gt(Carbon::now())){

			return true;
		}
		return false;
	}

	public function formatWorkouts($rawWorkouts, $daysOffset){

		$clientId = Auth::user()->clientid;

		$beginning = Carbon::today()->modify($daysOffset . ' days');
		$newWorkouts = [];

		foreach($rawWorkouts as $workout){

			$freePlacesCount = $this->countFreePlacesForWorkout($workout);
			$hasSubscriptionEntry = $this->hasClientSubscriptionAnEntry($workout, $clientId);
			$hasClientAnEntry = $this->hasClientAnEntry($workout, $clientId) ? true : $hasSubscriptionEntry;

			if($freePlacesCount > 0 || $hasClientAnEntry || $hasSubscriptionEntry){

				$coach = Coach::find($workout->COACHID);
				$coachName = Decoder::decodeName("$coach->SURNAME $coach->NAME");

				$newWorkout = [];
				$newWorkout["id"] = $workout->ID;
				$newWorkout["coachName"] = $coachName;
				$newWorkout["isCoachReplaced"] = $workout->ISCOACHREPLACED;

				$newWorkout['canEnter'] = $this->canEnter($workout);
				$newWorkout['hasEntry'] = $hasClientAnEntry;
				$newWorkout['hasSubscriptionEntry'] = $hasSubscriptionEntry;
				$newWorkout['placesCount'] = $freePlacesCount;

				$workoutName = WorkoutName::find($workout->WORKOUTNAMEID);
				$newWorkout['name'] = Decoder::decodeName($workoutName->NAME);

				$startDate = Carbon::parse($workout->START_EVENT);
				$x = $startDate->diff($beginning)->days;
				$y = number_format($startDate->format('H')+$startDate->format('i')/60,2);

				$newWorkout["starttime"] = $startDate->format('H:i');
				$newWorkout["endtime"] = Carbon::parse($workout->END_EVENT)->format('H:i');

				$newWorkouts[$x][$y] = $newWorkout;
				$newWorkouts[$x][number_format($y+0.25,2)] = '0';
				$newWorkouts[$x][number_format($y+0.5,2)] = '0';
				$newWorkouts[$x][number_format($y+0.75,2)] = '0';
			}
		}
		return $newWorkouts;
	}

	public function index($gymtype, $daysOffset = 0)
	{
		if($this->allowedGymName($gymtype)){

			$workouts = $this->getWorkouts($gymtype, (int)$daysOffset, $this->limitPeriodSinceToday);

			return MainClientController::renderViewWithClientInfo('client.schedule', [
				'tab'=>'sport',
				'title' => self::$gymNames[$gymtype],
				'period' => $this->getPeriodData($daysOffset),
				'tableHead' => $this->getTableHead($daysOffset),
				'workouts' => $this->formatWorkouts($workouts, $daysOffset),
				'gymtype' => $gymtype,
				'gymnames' => self::$gymNames
			]);
		}
	}

	public function enterWorkout()
	{
		if(Request::ajax()) {

			$data = Input::all();

			$workoutId = (int)$data['workoutId'];
			$workout = Workout::find($workoutId);

			if(isset($workout)){

				if(Carbon::parse($workout->START_EVENT)->gt(Carbon::now())){

					$unconfirmedWorkoutEntry = new UnconfirmedWorkoutEntry();
					$unconfirmedWorkoutEntry->WORKOUTID = $workout->ID;
					$unconfirmedWorkoutEntry->CLIENTID = Auth::user()->clientid;
					if($unconfirmedWorkoutEntry->save())
						return ['status' => 'success'];
					else
						return[
							'status' => 'fail',
							'msg' => 'Неизвестная ошибка'
						];

				}
				return[
					'status' => 'fail',
					'msg' => 'Тренировка уже прошла'
				];
			}
			else{

				return [
					'status' => 'fail',
					'msg' => 'Такой тренировки не существует'
				];

			}
		}
	}

	public function quitWorkout(){

		if(Request::ajax()) {

			$data = Input::all();

			$workoutId = (int)$data['workoutId'];
			$workout = Workout::find($workoutId);

			if (isset($workout)) {

				if(Carbon::parse($workout->START_EVENT)->gt(Carbon::now())){

					$clientId = Auth::user()->clientid;
					UnconfirmedWorkoutEntry::del("where WORKOUTID = " . $workoutId . " and CLIENTID = $clientId");

					return ['status' => 'success'];
				}
			}
		}
		return [
			'status' => 'fail',
			'msg' => 'Что-то пошло не так'
		];
	}
}
