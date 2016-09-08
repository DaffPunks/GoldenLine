<?php

namespace App\Services;

class Decoder{

    public static $encodings = ['Windows-1251', 'UTF-8'];

    public static function decodeString($value){

        if(gettype($value) == "string"){

            if(mb_detect_encoding($value, self::$encodings) != self::$encodings[1]){

                $value =  iconv(self::$encodings[0], self::$encodings[1], $value);
            }
        }
        return $value;

    }

    public static function decodeStringByParts($value){
        $letters = str_split($value);

        $result = "";
        foreach ($letters as $letter) {
            $result .= Decoder::decodeString($letter);
        }

        return $result;
    }

    public static function detectEncoding($value){

        return mb_detect_encoding($value, self::$encodings);

    }

    public static function encodeString($value){

        return iconv(self::$encodings[1], self::$encodings[0], $value);
    }

    public static function decodeName($someName){

        $name = Decoder::decodeString($someName);

        $acceptedStringPattern = "/^[a-zA-Z\p{Cyrillic}0-9\s\-]+$/u";
        if(! preg_match($acceptedStringPattern, $name)){
            $name = Decoder::decodeStringByParts($someName);
        }
        return $name;
    }
}