<?php

namespace App\Services;

class Checker{

    public static function isBool($value){

        return is_bool($value);
    }

    public static function isInteger($value){
        return is_int($value);
    }

    public static function isString($value){

        return true;
//        if(isset($value)){
//            $pattern = "/(;)+/g";
//
//            error_log('Hey');
//            error_log($value);
//            error_log(preg_match($pattern, $value));
//            error_log(preg_last_error());
//
//            if(preg_match($pattern, $value)){
//                return false;
//            }
//            else{
//                return true;
//            }
//        }
//        else{
//            return true;
//        }
//        return preg_match("/[a-zа-яё\d\s]*[a-zа-яё\d]$/i", $value);
    }

    public static function isDate($value){

        if(isset($value)){
            return strtotime($value);
        }
        else{
            return true;
        }
    }
}