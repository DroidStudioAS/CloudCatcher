<?php

namespace App\Helpers;

class WeatherHelper
{
    //this function just returns a string based on a random index,
    //while the second descriptionDeterminer() determines the most
    //likely description based on the generated temperature
    /******Const Arrays*****/
    const icons = [
        "sunny"=>"/res/sun.svg",
        "raining"=>"/res/rain.svg",
        "snowing"=>"/res/snow.svg",
        "cloudy"=>"/res/clouds.svg"
    ];
    const images = [
        0=> "/res/sunny.png",
        1=> "/res/rainy.png",
        2=>"/res/cloudy.png",
        3=>"/res/snowy.png"
    ];
    const descriptions=[
        "Sunny",
        "Raining",
        "Snowing",
        "Cloudy"
    ];
    /******Image Helpers*******/
    public static function determinePathToImage($description){
        $path_to_image=self::images[strtolower($description)];
        return $path_to_image;
    }
    public static function determineIconPath($description){
        $path_to_icon = self::icons[strtolower($description)];
        return $path_to_icon;
    }
    /*******Forecast Attribute Helpers*******/
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
    public static function realDataImagePathDeterminer($apiDescription){
        $lowerCaseApiDesc = strtolower($apiDescription);
        $description = "";
        //variants of rainy
        if(str_contains($lowerCaseApiDesc,"rain") || str_contains($lowerCaseApiDesc, "thunder")
            || str_contains($lowerCaseApiDesc,"drizzle")){
            return self::images[1];
        }
        //variants of snowy
        if(str_contains($lowerCaseApiDesc,"snow") || str_contains($lowerCaseApiDesc,"sleet")
            || str_contains($lowerCaseApiDesc,"blizzard") || str_contains($lowerCaseApiDesc,"ice")){
            return self::images[3];
        }
        //variants of sunny
        if(str_contains($lowerCaseApiDesc, "sunny") || str_contains($lowerCaseApiDesc,"clear")){
            return self::images[0];
        }
        //variants of cloudy
        if(str_contains($lowerCaseApiDesc,"fog") || str_contains($lowerCaseApiDesc,"cloudy")
            || str_contains($lowerCaseApiDesc,"overcast") || str_contains($lowerCaseApiDesc,"mist")){
            return self::images[2];
        }


        return $description;

    }



}


