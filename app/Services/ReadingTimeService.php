<?php

namespace App\Services;

class ReadingTimeService{
    public static function calculate($numOfLines){
        $time = $numOfLines / 200;
        $minutes = intval(floor($time));
        return $minutes;
    }
}