<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

use App\Models\Doctor;

use App\Services\Translator;
use App\Services\Decoder;
use App\Services\Checker;

use Illuminate\Support\Facades\Input;
use Request;

class ProceduresController extends Controller {

	private $departmentIndexes = [
		1000,
		2000,
		3000,
		4000,
		5000,
		6000,
		7000,
		8000
	];

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

	private function formatProcedures($procedures, $doctors){
		$newProcedures = [];
		foreach ($procedures as $procedure) {
			foreach($doctors as $doctor){
				if($procedure->DOCTORID == $doctor->ID){
					$newProcedure = [];

					$procedureTime = strtotime($procedure->START_EVENT);

					$newProcedure['time'] = date("H:i",$procedureTime);
					$newProcedure['endTime'] = date("H:i",strtotime($procedure->END_EVENT));
					$newProcedure['day'] = $this->getProcedureDay($procedureTime);
					$newProcedure['month'] = $this->getProcedureMonth($procedureTime);
					$newProcedure['comment'] = Decoder::decodeName($procedure->COMMENT);
					$newProcedure['doctorName'] = Decoder::decodeName("$doctor->SURNAME $doctor->NAME");

					$newProcedures[] = $newProcedure;

					break;
				}
			}
		}
		return $newProcedures;
	}

	private function getProceduresForTime($time, $offset, $amount){

		$currentUser = Auth::user();
		$clientId = $currentUser->clientid;

		if($time == 'future') {
			$dateOperator = '>=';
			$desc='';
		}else{
			$dateOperator = '<';
			$desc='desc';
		}

		$where = "WHERE CLIENTID = $clientId";
		//Put first departmentIndex in where
		$where .= ' AND DEPARTMENTID in (' . $this->departmentIndexes[0];

		//Start from second departmentIndex
		for($i = 1; $i < count($this->departmentIndexes); $i++){
			$departmentIndex = $this->departmentIndexes[$i];

			$where .= ', ' . $departmentIndex;
		}

		$where .= ')';

		$where .= " AND START_EVENT $dateOperator cast('NOW' as timestamp) AND MANIPULATIONSTATUSID <> 4 order by START_EVENT $desc";

		$procedures = Appointment::select("first $amount skip $offset CLIENTID, DOCTORID, COMMENT, START_EVENT, END_EVENT ", $where);

		return $procedures;
	}

	public function getDoctorsForProcedures($procedures){

		$length = count($procedures);
		if($length > 0) {

			$query = ' where ';
			$i = 0;
			foreach ($procedures as $procedure) {
				$id = $procedure->DOCTORID;

				if ($i < $length - 1) {
					$query .= " id=$id or ";
				} else if ($i == $length - 1) {
					$query .= " id=$id ";
				}

				$i++;
			}

			$doctors = Doctor::select('ID, NAME, SURNAME', $query);

			return $doctors;
		}
		else{
			return [];
		}
	}

	public function getProcedures() {

		if(Request::ajax())
		{
			$data = Input::all();

			$time = $data['time'];
			$offset = isset($data['offset']) ? (int)$data['offset'] : 0;
			$amount = isset($data['amount']) ? (int)$data['amount'] : 10;

			if(Checker::isInteger($offset) &&
				Checker::isInteger($amount)){

				$procedures = $this->getProceduresForTime($time, $offset, $amount);
				$doctors = $this->getDoctorsForProcedures($procedures);

				return $this->formatProcedures($procedures, $doctors);
			}
		}
	}

	public function index($time = 'future'){

		$isFuture = $time == 'future';

		return MainClientController::renderViewWithClientInfo('client.procedures', array('tab'=>'procedures', 'title' => 'Мои Процедуры', 'isFuture' => $isFuture));
	}
}
