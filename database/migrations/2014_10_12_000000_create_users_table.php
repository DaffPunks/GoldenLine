<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

include(__DIR__ . '/migrations_variables_fb.php');

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$query = 'CREATE TABLE "users" (
			"id" ' . __FB_ID__. __FB_NOT_NULL__ .
			', "cellphone" ' . __FB_STRING__ . __FB_NOT_NULL__ .
			', "password" ' . __FB_STRING__ . __FB_NOT_NULL__ .
			', "cabinetadminid" integer
			 , "clientid" integer
			 , "doctorid" integer
			 , "coachid" integer
			 , "remember_token" ' . __FB_STRING__ .
			', "created_at" timestamp '. __FB_NOT_NULL__ .
			', "updated_at" timestamp '. __FB_NOT_NULL__ .
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
	}

}
