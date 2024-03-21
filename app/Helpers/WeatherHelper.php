<?php

namespace App\Helpers;

class WeatherHelper
{
    //this function just returns a string based on a random index,
    //while the second descriptionDeterminer() determines the most
    //likely description based on the generated temperature
    const icons = [
        "sunny"=>"/res/sun.svg",
        "raining"=>"/res/rain.svg",
        "snowing"=>"/res/snow.svg",
        "cloudy"=>"/res/clouds.svg"
    ];
    const images = [
        "sunny"=> "/res/sunny.png",
        "raining"=> "/res/rainy.png",
        "cloudy"=>"/res/cloudy.png",
        "snowing"=>"/res/snowy.png"
    ];

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

 const descriptions=[
"Sunny",
"Raining",
"Snowing",
"Cloudy"
];
    public static function descriptionDeterminer($temp){
        if($temp<-5){
            //snowing if random num is 0 return sunny else return snowing
            return rand(0,1)===0? self::descriptions[0]:self::descriptions[2];
        }else if($temp>-5 && $temp<15){
            //cloudy if random num = - return rain else return cloudy
            return rand(0,1)===0? self::descriptions[1]:self::descriptions[3];
        }else if($temp>15){
            //if random num is 0 return raining else return sunny
            return rand(0,1)===0? self::descriptions[1]:self::descriptions[0];
        }
        return self::descriptions[0];
    }

    public static function determinePathToImage($description){
        $path_to_image=self::images[strtolower($description)];
        return $path_to_image;
    }
    public static function returnProbability($description){
        //sunny and cloudy
        if(strtolower($description)==="sunny" || strtolower($description)==="cloudy"){
            return null;
        }
        //returning 0 is kind of counter intuitive, because we are saying our own forecast is completely incorrect
        return rand(1,100);
    }
    public static function determineTemperatureColor($temperature){
        $color="";
        if ($temperature<0){
            $color="#bfe8f5";
        }
        else if($temperature>0&&$temperature<15){
            $color="#a9d8f7";
        }else if($temperature>15 &&$temperature<25){
            $color="#627e75";
        }else{
            $color="#e50000";
        }
        return $color;
    }

    public static function determineIconPath($description){
       $path_to_icon = self::icons[strtolower($description)];
       return $path_to_icon;
    }
}


