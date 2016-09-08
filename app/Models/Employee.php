<?php

namespace App\Models;

class Employee extends FBModel{
	public static $role = 1;

    public $connection = 'firebird_zl';
    public $table = 'EMPLOYEE';

}