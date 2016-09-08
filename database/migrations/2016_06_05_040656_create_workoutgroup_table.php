<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

include(__DIR__ . '/migrations_variables_fb.php');

class CreateWorkoutGroupTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$query = 'CREATE TABLE WORKOUTGROUP (
			 ID ' . __FB_ID__. __FB_NOT_NULL__ .
			', GROUPID integer ' . __FB_NOT_NULL__ .
			', WORKOUTID integer ' . __FB_NOT_NULL__ .
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
