<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnconfirmedWorkoutTriggers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//First Create Generator
		$query = 'CREATE GENERATOR GEN_UNCONFIRMEDWORKOUTENTRY_ID;';
		DB::unprepared($query);

		//Then Trigger
		$query = 'CREATE TRIGGER UNCONFIRMEDWORKOUTENTRYID for UNCONFIRMEDWORKOUTENTRY
						 active before insert position 0
						as
						begin
						  if ((new.id is null) or (new.id = 0)) then
						  begin
							new.id = gen_id(GEN_UNCONFIRMEDWORKOUTENTRY_ID, 1 );
						  end
						END;';
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
