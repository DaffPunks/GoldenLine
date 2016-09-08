<?php

namespace App\Models;

class Client extends FBModel{
	public static $role = 0;

    public $connection = 'firebird_zl';
    public $table = 'CLIENT';
}