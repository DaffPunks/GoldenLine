<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

include(__DIR__ . '/migrations_variables_fb.php');

class CreateWorkoutTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$query = 'CREATE TABLE WORKOUT (
			id ' . __FB_ID__. __FB_NOT_NULL__ .
			', COACHID integer
			 , WORKOUTNAMEID integer' . __FB_NOT_NULL__ .
			', CLIENTSCAPACITY integer
			 , GYMTYPE ' . __FB_STRING__ .
			', START_EVENT timestamp
			 , END_EVENT timestamp
			 , ISPERSONAL integer' . __FB_NOT_NULL__ .
			 ', ISCOACHREPLACED integer' . __FB_NOT_NULL__ .
			 ', ISACTIVE integer' . __FB_NOT_NULL__ .
			');';
		DB::unprepared($query);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
