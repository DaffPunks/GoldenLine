<?php

namespace App\Http\Controllers\AdminCoach;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\MainAdminController;

use App\Services\Decoder;
use App\Services\Checker;
use App\Services\Cleaner;

use App\Models\Coach;

use Illuminate\Support\Facades\Input;
use Request;

use Illuminate\Support\Facades\DB;

class AdminCoachesController extends Controller {


	public function __construct()
	{
		$this->middleware('auth');
	}

	public function formatCoaches($rawCoaches){

		$newCoaches = [];

		foreach($rawCoaches as $coach){

			$newCoach = [];
			$newCoach["id"] = $coach->ID;

			$firstName = $coach->NAME;
			trim($firstName);

			$surname = $coach->SURNAME;
			trim($surname);

			$secondName = $coach->SECONDNAME;
			trim($secondName);

			$name = Decoder::decodeName("$surname $firstName $secondName");

			$newCoach["name"] = $name;

			$newCoach['salary'] = $coach->BASESALARY;
			$newCoach['percent'] = $coach->SUBSCRIPTIONPERCENT;

			$newCoach["phone"] = "";
			$phoneParts = preg_split("/[\s,]+/", $coach->CELLPHONE);

			foreach($phoneParts as $phone){

				if(!is_numeric(substr($phone, 0, 1))) {

					$phone = Decoder::decodeString($phone);
				}

				$newCoach["phone"] .= $phone . " ";
			}

			$acceptedStringPattern = "/^[a-zA-Z\p{Cyrillic}0-9\s\-]+$/u";
			if(! preg_match($acceptedStringPattern, $newCoach["phone"])){
				$newCoach["phone"] = "!";//"Глупый мобильный";
			}
			trim($newCoach["phone"]);

			$newCoaches[] = $newCoach;
		}
		return $newCoaches;
	}

	public function index()
	{
		return MainAdminController::renderViewWithAdminInfo('admin.coaches.coaches', [
			'tab' => 'coaches',
			'title' => 'Тренера']);
	}

	public function search(){

		if(Request::ajax())
		{
			$data = Input::all();

			$skip = isset($data['offset']) ? (int)$data['offset'] : 0;
			$amount = isset($data['amount']) ? (int)$data['amount'] : 10;

			$searchQueryUTF8 = Cleaner::cleanString(mb_strtolower($data['search'],'UTF-8'));

			if(Checker::isInteger($skip) &&
				Checker::isInteger($amount) &&
				Checker::isString($searchQueryUTF8)
			){
				$searchQueryUTF8 = str_replace("-", "", $searchQueryUTF8);
				$searchQuery = iconv('utf-8','windows-1251',$searchQueryUTF8);

				$coaches = Coach::select(" first $amount skip $skip ID, NAME, SURNAME, SECONDNAME, CELLPHONE, BASESALARY, SUBSCRIPTIONPERCENT ", " where ISACTIVE =1 AND (lower(cast(SURNAME as varchar(255) character set WIN1251)) like '%$searchQuery%' or lower(cast(NAME as varchar(255) character set WIN1251)) like '%$searchQuery%' or lower(cast(SECONDNAME as varchar(255) character set WIN1251)) like '%$searchQuery%')");//DB::connection('firebird_cabinet')->select($query);
				$formattedCoaches = $this->formatCoaches($coaches);

				return $formattedCoaches;
			}
			else{
				return ['status' => 'fail'];
			}
		}
	}

	public function create()
	{
		if (Request::ajax()) {
			$data = Input::all();

			$surname = Cleaner::cleanString($data['surname']);
			$name = Cleaner::cleanString($data['name']);
			$secondName = Cleaner::cleanString($data['secondname']);
			$baseSalary = (float)$data['salary'];
			$subscriptionPercent = (float)$data['subscription_percent'];
			$cellphone = Cleaner::cleanString($data['cellphone']);

			if(Checker::isString($surname) &&
				Checker::isString($name) &&
				Checker::isString($secondName) &&
				Checker::isString($cellphone)){

				$newCoach = new Coach();
				$newCoach->SURNAME = Decoder::encodeString($surname);
				$newCoach->NAME = Decoder::encodeString($name);
				$newCoach->SECONDNAME = Decoder::encodeString($secondName);
				$newCoach->BASESALARY = $baseSalary;
				$newCoach->SUBSCRIPTIONPERCENT = $subscriptionPercent;
				$newCoach->CELLPHONE = Decoder::encodeString($cellphone);
				$newCoach->ISACTIVE = 1;

				if(!$newCoach->save()){
					$error = "Не удалось создать тренера";

					return [
						'status' => 'fail',
						'msg' => $error
					];
				}

				return [
					'status' => 'success'
				];
			}
			else{
				return ['status' => 'fail'];
			}
		}
	}

	public function edit()
	{
		if (Request::ajax()) {

			$data = Input::all();

			$coach_id = (int)$data['id'];
			$surname = Decoder::encodeString(Cleaner::cleanString($data['surname']));
			$name = Decoder::encodeString(Cleaner::cleanString($data['name']));
			$secondName = Decoder::encodeString(Cleaner::cleanString($data['secondname']));
			$baseSalary = (float)$data['salary'];
			$subscriptionPercent = (float)$data['subscription_percent'];
			$cellphone = Decoder::encodeString(Cleaner::cleanString($data['cellphone']));

			if(Checker::isInteger($coach_id) &&
				Checker::isString($surname) &&
				Checker::isString($name) &&
				Checker::isString($secondName) &&
				Checker::isString($cellphone)){

				$query = "update COACH set SURNAME = '$surname', NAME = '$name', SECONDNAME = '$secondName', BASESALARY = $baseSalary, SUBSCRIPTIONPERCENT = $subscriptionPercent, CELLPHONE = '$cellphone' where id = $coach_id";
				error_log($query);
				DB::connection('firebird_cabinet')->update($query);

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

	public function delete()
	{
		if (Request::ajax()) {
			$data = Input::all();

			$coach_id = (int)$data['id'];

			if(Checker::isInteger($coach_id)){

				$query = "update COACH set isactive = 0 where id = " . $coach_id;
				DB::connection('firebird_cabinet')->update($query);

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
