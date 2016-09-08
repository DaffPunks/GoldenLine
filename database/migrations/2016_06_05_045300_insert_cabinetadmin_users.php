<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Controllers\Register\FastRegisterController;

class InsertCabinetAdminUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$registerController = new FastRegisterController();
		$registerController->registerUser('101', '123456', 1, null, null, null);
		$registerController->registerUser('102', '123456', 2, null, null, null);
		$registerController->registerUser('103', '123456', 3, null, null, null);

		echo "Cabinet Admin Users Inserted";
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
