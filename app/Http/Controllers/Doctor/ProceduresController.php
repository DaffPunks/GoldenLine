<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

use App\Models\ManipulationStatus;
use App\Models\Client;

use App\Services\Translator;
use App\Services\Decoder;
use App\Services\Checker;

use Carbon\Carbon;

class ProceduresController extends Controller {

	function ACCEPTED_STRING_PATTERN(){
		return "/^[a-zA-Z\p{Cyrillic}0-9\s\-\.]+$/u";
	}

	public function __construct()
	{
		$this->middleware('auth');
	}

	private function getProcedureDay($procedureTime){

		$procedureDay = strtotime("midnight",$procedureTime);
		$yesterday = strtotime("yesterday");
		$today = strtotime("today");
		$tomorrow = strtotime("tomorrow");
		$afterTomorrow = strtotime("tomorrow",$tomorrow);

		if ($yesterday <= $procedureDay && $procedureDay < $today) {
			$day = "Вчера";
		} else if($today <= $procedureDay && $procedureDay < $tomorrow){
			$day = "Сегодня";
		} else if($tomorrow <= $procedureDay && $procedureDay < $afterTomorrow){
			$day = "Завтра";
		}else{
			$day = Translator::translateDay(date('l',$procedureTime));//" %a", date('d/m',$procedureTime);
		}

		return $day;
	}

	private function getProcedureMonth($procedureTime){
		$month = date('j',$procedureTime) . ' ' .  Translator::translateMonth(date('F',$procedureTime));
		return $month;
	}

	private function formatProcedures($procedures, $manipulationStatuses, $clients){
		$newProcedures = [];
		foreach ($procedures as $procedure) {
			foreach($clients as $client){
				if($procedure->CLIENTID == $client->ID){
					foreach($manipulationStatuses as $manipulationStatus){
						if($procedure->MANIPULATIONSTATUSID == $manipulationStatus->ID){

							$newProcedure = [];

							$procedureTime = strtotime($procedure->START_EVENT);

							$newProcedure['time'] = date("H:i",$procedureTime);
							$newProcedure['endTime'] = date("H:i",strtotime($procedure->END_EVENT));
							$newProcedure['day'] = $this->getProcedureDay($procedureTime);
							$newProcedure['month'] = $this->getProcedureMonth($procedureTime);
							$newProcedure['comment'] = Decoder::decodeName($procedure->COMMENT);
							$newProcedure['clientName'] = Decoder::decodeName($client->FIO);
							$newProcedure['manipulationStatusId'] = $manipulationStatus->ID;
							$newProcedure['manipulationStatus'] = Decoder::decodeName($manipulationStatus->NAME);

							$newProcedures[] = $newProcedure;

							break;
						}
					}
					break;
				}
			}
		}
		return $newProcedures;
	}

	public function getProceduresForTime($time){
		try {
			$parse = Carbon::parse($time);
		}
		catch (\Exception $err) {
			$parse = Carbon::today();
		}

		$statement = "START_EVENT > cast('" . $parse->toDateTimeString() . "' as timestamp) AND START_EVENT < cast('" . $parse->modify("+1 day")->toDateTimeString() . "' as timestamp) ";

		$registeredDoctorId = Auth::user()->doctorid;

		$eliminateUnnecessary = "and cast(START_EVENT as time) > cast('00:00' as time) and cast(START_EVENT as time) < cast('21:00' as time) and CLIENTID != 4320000 and clientid != 0 and clientid != 3675000 and clientid != 4109000";
		$procedures = Appointment::select(" CLIENTID, COMMENT, START_EVENT, END_EVENT, MANIPULATIONSTATUSID ", " where DOCTORID = $registeredDoctorId AND $statement $eliminateUnnecessary  order by START_EVENT asc");

		return $procedures;
	}

	public function getManipulationStatusesForProcedures($procedures){

		$length = count($procedures);
		if($length > 0) {

			$query = ' where ';
			$i = 0;
			foreach ($procedures as $procedure) {
				$id = $procedure->MANIPULATIONSTATUSID;

				if ($i < $length - 1) {
					$query .= " id=$id or ";
				} else if ($i == $length - 1) {
					$query .= " id=$id ";
				}

				$i++;
			}

			$manipulationStatuses = ManipulationStatus::select('ID, NAME', $query);

			return $manipulationStatuses;
		}
		else{
			return [];
		}
	}

	public function getClientsForProcedures($procedures){

		$length = count($procedures);
		if($length > 0) {

			$query = ' where ';
			$i = 0;
			foreach ($procedures as $procedure) {
				$id = $procedure->CLIENTID;

				if ($i < $length - 1) {
					$query .= " id=$id or ";
				} else if ($i == $length - 1) {
					$query .= " id=$id ";
				}

				$i++;
			}

			$clients = Client::select('ID, FIO', $query);

			return $clients;
		}
		else{
			return [];
		}
	}

	public function index($time = 'today'){

		if(Checker::isDate($time)){

			$procedures = $this->getProceduresForTime($time);
			$manipulationStatuses = $this->getManipulationStatusesForProcedures($procedures);
			$clients = $this->getClientsForProcedures($procedures);

			$formattedProcedures = $this->formatProcedures($procedures, $manipulationStatuses, $clients);

			return MainDoctorController::renderViewWithDoctorInfo('doctor.procedures', array('tab'=>'procedures', 'title' => 'Мои процедуры', 'procedures' => $formattedProcedures, 'time' => $time, 'isFuture' => false));
		}else{
			return '';
		}
	}
}
