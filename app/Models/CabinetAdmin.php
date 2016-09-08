<?php

namespace App\Models;


class CabinetAdmin extends FBModel{

    public $connection = 'firebird_cabinet';
    public $table = 'CABINETADMIN';
    public $timestamps = false;


}