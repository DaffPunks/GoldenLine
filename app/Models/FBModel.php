<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FBModel extends Model
{
    protected $primaryKey = 'ID';
    public $incrementing = false;

    public static $encodings = ['Windows-1251', 'UTF-8'];

    public static function select($beforeFromStatement, $afterFromStatement = ''){

        $connection = with(new static)->connection;
        $tableName  = with(new static)->getTable();
        $query = "select $beforeFromStatement from $tableName $afterFromStatement";
        return DB::connection($connection)->select($query);
    }

    public static function where($rawStatement){

        $connection = with(new static)->connection;
        $tableName  = with(new static)->getTable();
        return DB::connection($connection)->select("select * from $tableName where $rawStatement");
    }

    public static function upd($changes, $condition){

        $connection = with(new static)->connection;
        $tableName  = with(new static)->getTable();
        $query = "update $tableName $changes $condition";
        return DB::connection($connection)->update($query);
    }

    public static function del($afterStatement){

        $connection = with(new static)->connection;
        $tableName  = with(new static)->getTable();
        return DB::connection($connection)->delete("delete from $tableName $afterStatement");
    }
}
