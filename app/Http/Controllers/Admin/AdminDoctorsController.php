<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Services\Decoder;
use App\Services\Checker;
use App\Services\Cleaner;

use Illuminate\Support\Facades\Input;
use Request;
use Illuminate\Http\Response;
//use Illuminate\Contracts\Routing\ResponseFactory;

use Illuminate\Support\Facades\DB;
use App\Models\Doctor;
use App\Models\RegisteredDoctor;
use App\User;

class AdminDoctorsController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function formatDoctors($rawDoctors){

		$newDoctors = [];

		foreach($rawDoctors as $doctor){

			$element = array();
			$element["id"] = $doctor->ID;

			$firstname = $doctor->NAME;
			trim($firstname);
			$firstname = Decoder::decodeString($firstname);

			$surname = $doctor->SURNAME;
			trim($surname);
			$surname = Decoder::decodeString($surname);

			$secondName = $doctor->SECONDNAME;
			trim($secondName);
			$secondName = Decoder::decodeString($secondName);

			$name = $surname . ' ' . $firstname . ' ' . $secondName;

			$acceptedStringPattern = "/^[a-zA-Z\p{Cyrillic}0-9\s\-\.]+$/u";
			if(!preg_match($acceptedStringPattern, $name)){
				$name = Decoder::decodeStringByParts($doctor->NAME . ' ' . $doctor->SURNAME . ' '. $doctor->SECONDNAME);
				trim($name);
			}

			$element["name"] = $name;

			$element["phone"] = "";
			$phoneParts = preg_split("/[\s,]+/", $doctor->CELLPHONE);

			foreach($phoneParts as $phone){

				if(!is_numeric(substr($phone, 0, 1))) {

					$phone = Decoder::decodeString($phone);
				}

				$element["phone"] .= $phone . " ";
			}

			if(! preg_match($acceptedStringPattern, $element["phone"])){
				$element["phone"] = "Глупый мобильный";
			}
			trim($element["phone"]);

			$element["status"] = $doctor->ISACTIVE == '1' ? 'Активный' : 'Неактивный';
			$lastActiveDate = strtotime($doctor->LASTOPERATIONDATE);
			$element["lastDate"] = date('j.n.y    H:m', $lastActiveDate);

//			if(isset($doctor->POSITIONID)){
//				$positionId = $doctor->POSITIONID;
//				$position = DB::connection('firebird_zl')->select('select * from "POSITION" where ID = ' . "'" .$positionId . "'")[0]->NAME;
//				$element["position"] = Decoder::decodeString($position);
//			}else{
//				$element["position"] = "Неизвестно";
//			}

			$newDoctors[] = $element;
		}
		return $newDoctors;
	}

	public function index()
	{
		return MainAdminController::renderViewWithAdminInfo('admin.specialists.specialists', [
			'tab' => 'specialists',
			'title' => 'Специалисты']);
	}

	public function search(){

		if(Request::ajax())
		{
			$data = Input::all();

			$skip = isset($data['offset']) ? (int)$data['offset'] : 0;
			$amount = isset($data['amount']) ? (int)$data['amount'] : 20;

			$searchQueryUTF8 = Cleaner::cleanString(mb_strtolower($data['search'],'UTF-8'));

			if(Checker::isInteger($skip) &&
				Checker::isInteger($amount) &&
				Checker::isString($searchQueryUTF8)
			){
				$searchQueryUTF8 = str_replace("-", "", $searchQueryUTF8);
				$searchQuery = Decoder::encodeString($searchQueryUTF8);

				//Firebird 2.5 query
//			$query = "select first $amount skip $skip * from DOCTOR desc where (id != 0) AND (SURNAME like '%$searchQuery%' or NAME like '%$searchQuery%' or SECONDNAME like '%$searchQuery%')";

				//Firebird 2.0 query
				$columns = " r.ID, r.SURNAME, r.NAME, r.SECONDNAME, r.CELLPHONE, r.ISACTIVE, r.LASTOPERATIONDATE ";
				$query = "select first $amount skip $skip $columns from DOCTOR r where (id != 0) AND (
				lower(cast(SURNAME as varchar(255) character set WIN1251)) like '%$searchQuery%' or
				lower(cast(NAME as varchar(255) character set WIN1251)) like '%$searchQuery%' or
				lower(cast(SECONDNAME as varchar(255) character set WIN1251)) like '%$searchQuery%')";

				$doctors = DB::connection('firebird_zl')->select($query);
				$formattedDoctors = $this->formatDoctors($doctors);

				return $formattedDoctors;
			}
			else{
				return ['status' => 'fail'];
			}
		}
	}

	public function getUserID($doctorId) {

		$user = DB::connection('firebird_cabinet')->select('select "id" from "users" where "doctorid" = ' . $doctorId);
		if(count($user)>0){
			return $user[0]->id;
		}
		return 0;
	}

	public function generatePassword(){

		if(Request::ajax())
		{
			$data = Input::all();
			$cellphone = Cleaner::cleanString($data['cellphone']);
			$doctorId = (int)$data['doctorId'];

			if(Checker::isString($cellphone) &&
				Checker::isInteger($doctorId)) {

				//Generate Password
				$password = "";
				$chars = 'abcdefghijklmnopqrstuvwxyz0123456789'; //abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789
				$charsNum = strlen($chars);
				for($i=0;$i<6;++$i){
					$password .= $chars[rand(0,$charsNum-1)];
				}

				$userID = $this->getUserID($doctorId);

				if($userID == 0){

					$this->registerDoctor($doctorId);

					$user = new User();
					$user->cellphone = $cellphone;
					$user->password = Hash::make($password);
					$user->doctorid = $doctorId;
					$user->save();
				}else{
					$user = User::find($userID);
					$user->cellphone = $cellphone;
					$user->password = Hash::make($password);
					$user->save();
				}

				return $password;
			}
			else{
				return [
					'status' => 'fail'
				];
			}
		}
	}

	function registerDoctor($doctorId){

		$doctor = Doctor::find($doctorId);

		$registeredDoctor = new RegisteredDoctor();
		$registeredDoctor->SALOONID = $doctorId;
		$registeredDoctor->NAME = $doctor->NAME;
		$registeredDoctor->SURNAME = $doctor->SURNAME;
		$registeredDoctor->SECONDNAME = $doctor->SECONDNAME;
		$registeredDoctor->BIRTHDATE = $doctor->BIRTHDATE;
		$registeredDoctor->SHORTFIO = $doctor->SHORTFIO;
		$registeredDoctor->save();
	}
}
