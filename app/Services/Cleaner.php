<?php

namespace App\Services;

class Cleaner{

    public static function cleanString($string){

        if(isset($string) && $string != ''){
            $regex = "/([';\"])+/u";
            $string = preg_replace($regex,'', $string);
        }
        return $string;
    }
}