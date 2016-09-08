<?php

use Illuminate\Support\Facades\Auth;

Route::get('/home',[
	function(){
		return Redirect::to("/");
	}
]);

Route::get('/',[

	'middleware' => 'auth',
	function(){
		$currentUser = Auth::user();

		if($currentUser->doctorid != null){
			return Redirect::to("/specialist/procedures/today");
		}
		elseif($currentUser->clientid != null){
			return Redirect::to("/procedures/future");
		}
		elseif($currentUser->cabinetadminid == 1){
			return Redirect::to("/admin/clients");
		}
		elseif($currentUser->cabinetadminid == 2){
			return Redirect::to("/sport/big");
		}
		elseif($currentUser->cabinetadminid == 3){
			return Redirect::to("/sport/big");
		}
}]);

//!Client

//Promos are turned off right now
//Route::get('/promos','Client\PromosController@index');

//get
Route::get('/procedures',[
	'middleware' => 'client',
	'before'=>'csrf',
	function(){
		return Redirect::to("/procedures/future");
}]);

Route::get('/procedures/{time}', [
	'middleware' => 'client',
	'before'=>'csrf',
	'uses' => 'Client\ProceduresController@index'
]);

//post
Route::post('/get_account_data', [
	'middleware' => 'client',
	'before'=>'csrf',
	'uses' => 'Client\ClientAccountController@getAccountData'
]);

Route::post('/get_procedures', [
	'middleware' => 'client',
	'before'=>'csrf',
	'uses' => 'Client\ProceduresController@getProcedures'
]);

Route::post('/get_phone', [
	'middleware' => 'client',
	'before'=>'csrf',
	'uses' => 'Client\CallMeController@getPhoneNumber'
]);

Route::post('/call_me', [
	'middleware' => 'client',
	'before'=>'csrf',
	'uses' => 'Client\CallMeController@callMe'
]);

Route::group(['prefix' => 'sport', 'middleware' => 'client', 'before'=>'csrf'], function(){

	Route::post('/enterWorkout', [
		'uses' => 'Client\ScheduleController@enterWorkout'
	]);

	Route::post('/quitWorkout', [
		'uses' => 'Client\ScheduleController@quitWorkout'
	]);

});

//!Admin
Route::group(['prefix' => 'admin', 'middleware' => 'admin', 'before' => 'csrf'], function () {

	Route::post('/clients/search', [
		'uses' => 'Admin\AdminClientsController@search'
	]);

});

Route::group(['prefix' => 'admin', 'middleware' => 'admin_cosmetics', 'before' => 'csrf'], function () {

	Route::get('/', function(){
		return Redirect::to("/admin/clients");
	});

	//get
	Route::get('/clients', [
		'uses' => 'Admin\AdminClientsController@index'
	]);

	Route::get('/specialists', [
		'uses' => 'Admin\AdminDoctorsController@index'
	]);

	//post
	//clients
	Route::post('/clients/getid/{clientId}', array(
		'uses' => 'Admin\AdminClientsController@getUserID'
	));

	Route::post('/clients/genpassword', array(
		'uses' => 'Admin\AdminClientsController@generatePassword'
	));

	//specialists
	Route::post('/specialists/search', array(
		'uses' => 'Admin\AdminDoctorsController@search'
	));

	Route::post('/specialists/getid/{doctorId}', array(
		'uses' => 'Admin\AdminDoctorsController@getUserID'
	));

	Route::post('/specialists/genpassword', array(
		'uses' => 'Admin\AdminDoctorsController@generatePassword'
	));
});

Route::group(['prefix' => 'admin', 'middleware' => 'admin_sport', 'before' => 'csrf'], function () {

	//workout names
	Route::post('/workout/search/name', [
		'uses' => 'Admin\AdminWorkoutNamesController@search'
	]);

	Route::post('/workout/create', [
		'uses' => 'Admin\AdminWorkoutsController@createWorkout'
	]);

	Route::post('/workout/get_entry_data', [
		'uses' => 'Admin\AdminScheduleController@getEntryData'
	]);

	Route::post('/workout/update', [
		'uses' => 'Admin\AdminWorkoutsController@updateWorkout'
	]);

	Route::post('/workout/delete', [
		'uses' => 'Admin\AdminWorkoutsController@deleteWorkout'
	]);

	Route::post('/workout/name/delete', [
		'uses' => 'Admin\AdminWorkoutNamesController@deleteWorkoutName'
	]);

	//post
	//coaches search
	Route::post('/coaches/search', [
		'uses' => 'AdminCoach\AdminCoachesController@search'
	]);

	//Subscription search
	Route::post('/subscriptions/search', [
		'uses' => 'Admin\AdminScheduleController@searchSubscriptionsForWorkout'
	]);
});

Route::group(['prefix' => 'admin', 'middleware' => 'admin_coach', 'before' => 'csrf'], function () {

	Route::get('/', function(){
		return Redirect::to("/sport/big");
	});

	Route::get('/coaches', [
		'middleware' => 'admin_coach',
		'uses' => 'AdminCoach\AdminCoachesController@index'
	]);

	//post
	Route::post('/coaches/create', [
		'middleware' => 'admin_coach',
		'uses' => 'AdminCoach\AdminCoachesController@create'
	]);

	Route::post('/coaches/edit', [
		'middleware' => 'admin_coach',
		'uses' => 'AdminCoach\AdminCoachesController@edit'
	]);

	Route::post('/coaches/delete', [
		'middleware' => 'admin_coach',
		'uses' => 'AdminCoach\AdminCoachesController@delete'
	]);
});

//!Doctor
//get
Route::group(['prefix' => 'specialist', 'middleware' => 'doctor'], function () {

	Route::get('/procedures', function(){

		return Redirect::to("/procedures/today");
	});

	Route::get('/procedures/{time}', [
		'uses' => 'Doctor\ProceduresController@index'
	]);
});

//Common
//get
Route::get('/sport/{path}/{daysOffset?}',[ 'middleware' => 'auth', function($path, $daysOffset = 0){

	$currentUser = Auth::user();

	if($path == 'big' || $path == 'small'){ //|| $path == 'fitness'

		if($currentUser->clientid != null){
			return App::make('App\Http\Controllers\Client\ScheduleController')->index($path, $daysOffset);
		}
		elseif($currentUser->cabinetadminid == 2 || $currentUser->cabinetadminid == 3){
			return App::make('App\Http\Controllers\Admin\AdminScheduleController')->index($path, $daysOffset);
		}

	}else if($path == 'subscriptions' && $currentUser->clientid != null){
		return App::make('App\Http\Controllers\Client\SubscriptionsController')->index();
	}
}]);

//post
Route::post( '/change_password', array(
	'middleware' => 'auth',
	'before'=>'csrf',
	'uses' => 'Common\ChangePasswordController@index'
));

//Auth

Route::get('/login', function(){

	if(Auth::check()){
		return Redirect::to("/");
	}else{
		return view('auth/login');
	}
});
Route::post('/auth/login', 'Auth\AuthController@postLogin');

Route::get('/logout', 'Auth\AuthController@getLogout');

//Route::get('/register', function(){
//	if(Auth::check()){
//		return Redirect::to("/");
//	}else{
//		return view('auth/register');
//	}
//});

//Route::post('/auth/register', 'Auth\AuthController@postRegister');

//Route::get('/db',function(){
//	$client = App\Models\Client::find(1);
//	echo $client->NAME;
//});

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
