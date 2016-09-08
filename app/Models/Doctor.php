<?php

namespace App\Models;

class Doctor extends FBModel{
	public static $role = 2;

    public $connection = 'firebird_zl';
    public $table = 'DOCTOR';

}