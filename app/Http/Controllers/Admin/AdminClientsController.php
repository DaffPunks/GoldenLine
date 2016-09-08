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
use App\Models\Client;
use App\Models\RegisteredClient;
use App\User;

class AdminClientsController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function formatClients($rawClients){

		$newClients = [];

		foreach($rawClients as $client){

			$element = array();
			$element["id"] = $client->ID;

//			$firstname = $client->NAME;
//			trim($firstname);
//			$firstname = Decoder::decodeString($firstname);
//
//			$surname = $client->SURNAME;
//			trim($surname);
//			$surname = Decoder::decodeString($surname);
//
//			$secondName = $client->SECONDNAME;
//			trim($secondName);
//			$secondName = Decoder::decodeString($secondName);
//
//			$name = $surname . ' ' . $firstname . ' ' . $secondName;

			$fio = $client->FIO;
			trim($fio);
			$name = Decoder::decodeName($fio);
			$element["name"] = $name;

			$element["phone"] = "";
			$phoneParts = preg_split("/[\s,]+/", $client->CELLPHONE);

			foreach($phoneParts as $phone){

				if(!is_numeric(substr($phone, 0, 1))) {

					$phone = Decoder::decodeString($phone);
				}

				$element["phone"] .= $phone . " ";
			}

			$acceptedStringPattern = "/^[a-zA-Z\p{Cyrillic}0-9\s\-]+$/u";
			if(!preg_match($acceptedStringPattern, $element["phone"])){
				$element["phone"] = "!";
			}
			trim($element["phone"]);

			$element["status"] = $client->ISACTIVE == '1' ? 'Активный' : 'Неактивный';
			$lastActiveDate = strtotime($client->LASTOPERATIONDATE);
			$element["lastDate"] = date('j.n.y    H:m', $lastActiveDate);

			$newClients[] = $element;
		}
		return $newClients;
	}

	public function index()
	{
		return MainAdminController::renderViewWithAdminInfo('admin.clients.clients', [
			'tab' => 'clients',
			'title' => 'Клиенты']);
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
//			$query = "select first $amount skip $skip * from CLIENT desc where id!=0 AND lower(cast(REPLACE(FIO2,'-','') as varchar(255) character set WIN1251)) like '%$searchQuery%'";

				//Firebird 2.0 query
				$columns = " r.ID, r.CELLPHONE, r.ISACTIVE, r.LASTOPERATIONDATE, r.FIO, r.FIO2 ";
				$query = "select first $amount skip $skip $columns from CLIENT r where id!=0 AND lower(cast(FIO2 as varchar(255) character set WIN1251)) like '%$searchQuery%' order by ID descending;";

				$clients = DB::connection('firebird_zl')->select($query);
				$formattedClients = $this->formatClients($clients);

				return $formattedClients;
			}
			else{
				return ['status' => 'fail'];
			}
		}
	}

	public function getUserID($clientId) {

		$user = DB::connection('firebird_cabinet')->select('select "id" from "users" where "clientid" = ' . $clientId);
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
			$clientId = (int)$data['clientId'];

			if(Checker::isString($cellphone) &&
				Checker::isInteger($clientId)){
				//Generate Password
				$password = "";
				$chars = 'abcdefghijklmnopqrstuvwxyz0123456789'; //abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789
				$charsNum = strlen($chars);
				for($i=0;$i<6;++$i){
					$password .= $chars[rand(0,$charsNum-1)];
				}

				$userID = $this->getUserID($clientId);

				if($userID == 0){

					$this->registerClient($clientId);

					$user = new User();
					$user->cellphone = $cellphone;
					$user->password = Hash::make($password);
					$user->clientid = $clientId;
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

	function registerClient($clientId){

		$client = Client::find($clientId);

		$registeredClient = new RegisteredClient();
		$registeredClient->SALOONID = $clientId;
		$registeredClient->NAME = $client->NAME;
		$registeredClient->SURNAME = $client->SURNAME;
		$registeredClient->SECONDNAME = $client->SECONDNAME;
		$registeredClient->SEXID = $client->SEXID;
		$registeredClient->BIRTHDATE = $client->BIRTHDATE;
		$registeredClient->SHORTFIO = $client->SHORTFIO;
		$registeredClient->save();
	}
}
