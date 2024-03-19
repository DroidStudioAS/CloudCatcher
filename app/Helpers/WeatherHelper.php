<?php

namespace App\Helpers;

class WeatherHelper
{
    public static function determineDescriptionString($index){
        $descriptionString = "";
        switch ($index){
            case 1:
                $descriptionString= "sunny";
                break;
            case 2:
                $descriptionString =  "cloudy";
                break;
            case 3:
                $descriptionString = "raining";
                break;
            case 4:
                $descriptionString = "snowing";
                break;
        }
        return $descriptionString;
    }
    public static function descriptionDeterminer($temp){
        if($temp<-5){
            //snowing
            return 2;
        }else if($temp>-5 && $temp<5){
            //cloudy
            return 3;
        }else if($temp>15){
            return 0;
        }
        return 1;
    }
}


