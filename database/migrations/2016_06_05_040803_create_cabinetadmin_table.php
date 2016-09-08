<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

include(__DIR__ . '/migrations_variables_fb.php');

class CreateCabinetAdminTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$query = 'CREATE TABLE CABINETADMIN (
			 ID ' . __FB_ID__. __FB_NOT_NULL__ .
			', SURNAME ' . __FB_STRING__ .
			', NAME ' . __FB_STRING__ .
			', SECONDNAME ' . __FB_STRING__ .
			', SHORTFIO ' . __FB_STRING__ .
			', ADMINROLEID ' . __FB_INTEGER__ .
			', EMPLOYEEID ' . __FB_INTEGER__ .
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
