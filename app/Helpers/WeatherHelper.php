<?php

namespace App\Helpers;

class WeatherHelper
{
    //this function just returns a string based on a random index,
    //while the second descriptionDeterminer() determines the most
    //likely description based on the generated temperature
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
    public static function determinePathToImage($description){
        $path_to_image="";
        switch ($description){
            case "sunny":
                $path_to_image="/res/sunny.png";
                break;
            case "raining":
                $path_to_image="/res/rainy.png";
                break;
            case "cloudy":
                $path_to_image="/res/cloudy.png";
                break;
            case "snowing":
                $path_to_image="/res/snowy.png";
                break;
        }
        return $path_to_image;
    }
    public static function returnProbability($description){
        //sunny and cloudy
        if($description===0 || $description===3){
            return null;
        }
        //returning 0 is kind of counter intuitive, because we are saying our own forecast is completely incorrect
        return rand(1,100);
    }
}


