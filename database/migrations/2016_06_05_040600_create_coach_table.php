<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

include(__DIR__ . '/migrations_variables_fb.php');

class CreateCoachTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$query = 'CREATE TABLE COACH (
			 ID ' . __FB_ID__. __FB_NOT_NULL__ .
			', NAME ' . __FB_STRING__ . __FB_NOT_NULL__ .
			', SURNAME ' . __FB_STRING__ .
			', SECONDNAME ' . __FB_STRING__ .
			', CELLPHONE ' . __FB_STRING__ .
			', BASESALARY ' . __FB_DECIMAL__ .
			', SUBSCRIPTIONPERCENT ' . __FB_DECIMAL__ .
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
