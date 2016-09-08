<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

include(__DIR__ . '/migrations_variables_fb.php');

class CreateWorkoutNameTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$query = 'CREATE TABLE WORKOUTNAME (
			 ID ' . __FB_ID__. __FB_NOT_NULL__ .
			', NAME ' . __FB_STRING__ . __FB_NOT_NULL__ .
			', ISACTIVE integer ' . __FB_NOT_NULL__ .
			', "created_at" '. __FB_TIMESTAMP__ .
			', "updated_at" '. __FB_TIMESTAMP__ .
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
