<?php

namespace App\Services;

class Translator{

    public static function getMonthInRus($monthNum){

        $ruMonth = [
            'Января',
            'Февраля',
            'Марта',
            'Апреля',
            'Мая',
            'Июня',
            'Июля',
            'Августа',
            'Сентября',
            'Октября',
            'Ноября',
            'Декабря'
        ];

        return $ruMonth[$monthNum-1];
    }

    public static function translateMonth($monthInEng){

        $engMonth = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'];



        $index = array_search($monthInEng, $engMonth);

        return self::getMonthInRus($index+1);
    }

    public static function translateDay($dayInEng){

        $engDay = [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday'
        ];

        $ruDay = [
            'Понедельник',
            'Вторник',
            'Среда',
            'Четверг',
            'Пятница',
            'Суббота',
            'Воскресенье'
        ];

        $index = array_search($dayInEng, $engDay);
        return $ruDay[$index];
    }

    public static function translateDayShort($dayNumUS){

        $ruDay = [
            'Вс',
            'Пн',
            'Вт',
            'Ср',
            'Чт',
            'Пт',
            'Сб'
        ];

        return $ruDay[$dayNumUS];
    }
}