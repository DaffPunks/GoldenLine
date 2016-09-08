<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\CabinetAdmin;

class InsertCabinetAdmins extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$cosmetics_admin = new CabinetAdmin();
		$cosmetics_admin->SURNAME = "АДМИНИСТРАТОР";
		$cosmetics_admin->ADMINROLEID = 1;
		$cosmetics_admin->save();

		$sport_admin = new CabinetAdmin();
		$sport_admin->SURNAME = "АДМИН";
		$sport_admin->NAME = "СПОРТ";
		$sport_admin->ADMINROLEID = 2;
		$sport_admin->save();

		$coach_admin = new CabinetAdmin();
		$coach_admin->SURNAME = "Разинкина";
		$coach_admin->NAME = "Ксения";
		$coach_admin->ADMINROLEID = 3;
		$coach_admin->save();

		echo "Cabinet Admins Inserted";
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
