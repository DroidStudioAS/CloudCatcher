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
    const extendedDescriptions = [
        'partly cloudy' => 'Mix Of Sun And Clouds, Changing Sky Conditions',
        'sunny' => 'Clear Skies, Abundant Sunshine, Perfect For Outdoor Activities',
        'cloudy' => 'Overcast Sky, No Direct Sunlight, Dim Light',
        'overcast' => 'Complete Cloud Cover, Dull Atmosphere, Limited Visibility',
        'mist' => 'Light Fog, Slightly Reduced Visibility, Damp Surroundings',
        'patchy rain possible' => 'Occasional Light Rain Showers, Intermittent Wet Conditions',
        'patchy snow possible' => 'Chance Of Light Snowfall, Scattered Snowflakes',
        'patchy sleet possible' => 'Possibility Of Light Sleet Showers, Mixed Precipitation',
        'patchy freezing drizzle possible' => 'Chance Of Light Freezing Drizzle, Icy Conditions',
        'thundery outbreaks possible' => 'Possibility Of Thunderstorms, Electric Activity In The Sky',
        'blowing snow' => 'Snow Being Blown By Strong Winds, Reduced Visibility',
        'blizzard' => 'Severe Snowstorm With Strong Winds, Near-Zero Visibility, Hazardous Travel',
        'fog' => 'Thick Fog, Limited Visibility, Misty Atmosphere',
        'freezing fog' => 'Fog With Below-Freezing Temperatures, Icy Surfaces, Hazardous Driving',
        'patchy light drizzle' => 'Occasional Light Drizzle, Damp Conditions, Wet Surfaces',
        'light drizzle' => 'Continuous Light Rain, Persistent Dampness, Light Precipitation',
        'freezing drizzle' => 'Rain Freezing Upon Contact, Icy Surfaces, Slippery Conditions',
        'heavy freezing drizzle' => 'Intense Freezing Rain, Hazardous Driving Conditions, Icy Roads',
        'patchy light rain' => 'Sporadic Light Rain Showers, Occasional Dampness, Scattered Drops',
        'patchy rain nearby' => 'Sporadic Light Rain Showers, Occasional Dampness, Scattered Drops',
        'light rain' => 'Persistent Light Rain, Wet Conditions, Umbrella Weather',
        'moderate rain at times' => 'Periods Of Moderate Rain, Varying Intensity, Wet Conditions',
        'moderate rain' => 'Steady Rainfall, Moderate Intensity, Damp Surroundings',
        'heavy rain at times' => 'Periods Of Heavy Rain, Intense Downpours, Localized Flooding',
        'heavy rain' => 'Continuous Heavy Rainfall, Strong Intensity, Significant Accumulation',
        'light freezing rain' => 'Light Rain Freezing Upon Contact, Icy Surfaces, Slippery Conditions',
        'moderate or heavy freezing rain' => 'Intense Freezing Rain, Hazardous Driving, Icy Roads',
        'light sleet' => 'Light Icy Pellets, Mixed Precipitation, Slippery Conditions',
        'moderate or heavy sleet' => 'Intense Icy Pellets, Hazardous Driving, Slippery Surfaces',
        'patchy light snow' => 'Occasional Light Snow Showers, Scattered Flakes, Cold Conditions',
        'light snow' => 'Gentle Snowfall, Light Accumulation, Picturesque Scenery',
        'patchy moderate snow' => 'Occasional Moderate Snow Showers, Intermittent Accumulation',
        'moderate snow' => 'Steady Snowfall, Moderate Accumulation, Winter Wonderland',
        'patchy heavy snow' => 'Occasional Heavy Snow Showers, Intermittent Accumulation',
        'heavy snow' => 'Intense Snowfall, Heavy Accumulation, Winter Storm Conditions',
        'ice pellets' => 'Pellets Of Ice Falling From The Sky, Hazardous Road Conditions',
        'light rain shower' => 'Brief Light Rainfall, Passing Shower, Temporary Wetness',
        'moderate or heavy rain shower' => 'Intense Rainfall In Brief Periods, Heavy Droplets',
        'torrential rain shower' => 'Heavy, Violent Rainfall, Extreme Downpour',
        'light sleet showers' => 'Brief Light Icy Pellets, Passing Showers, Slippery Conditions',
        'moderate or heavy sleet showers' => 'Intense Icy Pellets In Brief Periods, Hazardous Driving',
        'light snow showers' => 'Brief Light Snowfall, Passing Showers, Scattered Flakes',
        'moderate or heavy snow showers' => 'Intense Snowfall In Brief Periods, Accumulating Snow',
        'light showers of ice pellets' => 'Brief Pellets Of Ice Falling From Sky, Slippery Conditions',
        'moderate or heavy showers of ice pellets' => 'Intense Pellets Of Ice Falling, Hazardous Road Conditions',
        'patchy light rain with thunder' => 'Occasional Light Rain With Thunder, Passing Storm',
        'moderate or heavy rain with thunder' => 'Intense Rainfall With Thunder, Electric Activity',
        'patchy light snow with thunder' => 'Occasional Light Snow With Thunder, Passing Winter Storm',
        'moderate or heavy snow with thunder' => 'Intense Snowfall With Thunder, Winter Storm Conditions',
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

    public static function presentationDescriptionDeterminer($description){
        return self::extendedDescriptions[trim(strtolower($description))];
    }

    /*****AQI****/
    public static function aqiShortDescriptionDeterminer($index){
        $desc="";
        switch ($index){
            case 1:
                $desc = "Good";
                break;
            case 2:
                $desc= "Moderate";
                break;
            case 3:
                $desc = "Unhealthy For Sensitive Groups";
                break;
            case 4:
                $desc="Unhealthy";
                break;
            case 5:
                $desc = "Very Unhealthy";
                break;
            case 6:
                $desc="Hazardous";
                break;
        }
        return $desc;
    }
    public static function aqiLongDescriptionDeterminer($index){
        $desc="";
        switch ($index){
            case 1:
                $desc = "Air quality is good, suitable for outdoor activities and prolonged exposure.";
                break;
            case 2:
                $desc = "Air quality is moderate, may cause some discomfort for sensitive individuals.";
                break;
            case 3:
                $desc = "Air quality is unhealthy for sensitive groups, limit outdoor activities.";
                break;
            case 4:
                $desc = "Air quality is unhealthy, reduce prolonged outdoor or strenuous activities.";
                break;
            case 5:
                $desc = "Air quality is very unhealthy, avoid outdoor activities, especially for sensitive individuals.";
                break;
            case 6:
                $desc = "Air quality is hazardous, stay indoors, and keep outdoor exposure to a minimum.";
                break;
        }
        return $desc;
    }
    public static function aqiBackgroudDeterminer($index){
        $color="";
        switch ($index){
            case 1:
                $color = "#8BC34A";
                break;
            case 2:
                $color = "#FFD54F";
                break;
            case 3:
                $color = "#FF9800";
                break;
            case 4:
                $color = "#F44336";
                break;
            case 5:
                $color = "#9C27B0";
                break;
            case 6:
                $color = "#757575";
                break;
        }
        return $color;
    }

    /****AQI END***/

    public static function dailyForecastBackgroundDeterminer($description){
        $path_to_image = collect([]);
        $trimedLowerCaseDesc = trim(strtolower($description));
        if (str_contains($trimedLowerCaseDesc, "sunny")) {
            $path_to_image->push("#77b5cb");
            $path_to_image->push("res/back/back_sunny.jpg");
            $path_to_image->push("#fff");
        } elseif (str_contains($trimedLowerCaseDesc, "cloudy")) {
            $path_to_image->push("#435e7e");
            $path_to_image->push("res/back/back_partly_cloudy.jpg");
            $path_to_image->push("#fff");
        }elseif(str_contains($trimedLowerCaseDesc, "rain") || str_contains($trimedLowerCaseDesc, "drizzle")){
            $path_to_image->push("#525c69");
            $path_to_image->push("res/back/back_rainy.jpg");
            $path_to_image->push("#fff");
        }elseif(str_contains($trimedLowerCaseDesc, "overcast")|| str_contains($trimedLowerCaseDesc,"thunder")){
            $path_to_image->push("#5d595c");
            $path_to_image->push("res/back/back_overcast.jpg");
            $path_to_image->push("#fff");
        }elseif(str_contains($trimedLowerCaseDesc, "fog")){
            $path_to_image->push("#989ea6");
            $path_to_image->push("res/back/back_fog.jpg");
            $path_to_image->push("#fff");
        }elseif(str_contains($trimedLowerCaseDesc, "snow") || str_contains($trimedLowerCaseDesc,"blizzard")
            || str_contains($trimedLowerCaseDesc, "pellets") || str_contains($trimedLowerCaseDesc,"sleet")){
            $path_to_image->push("#8ccbe6");
            $path_to_image->push("res/back/back_snow.jpg");
            $path_to_image->push("#000");

        }
        return $path_to_image;
    }



}


