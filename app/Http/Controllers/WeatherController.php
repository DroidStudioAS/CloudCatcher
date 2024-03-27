<?php

namespace App\Http\Controllers;

use App\Helpers\WeatherHelper;
use App\Models\CityModel;
use App\Models\ForecastModel;
use App\Models\WeatherModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isEmpty;


class WeatherController extends Controller
{
    //index method
    public function loadTodaysWeathers(){
        $date = Carbon::today()->format('Y-m-d');

        $weathers = ForecastModel::with("city")->where("date", $date)->paginate(6);

        //return favorites
        $favoriteCities = Auth::user()->cityFavorites;

        return view("welcome", compact('weathers', 'date',"favoriteCities"));
    }


    //will always return the 5 latest forecasts
    public function getWeatherForecastForCity($city){
      $forecast = CityModel::where("city_name",$city)->with(["forecast"=> function($query){
            $query->latest()->limit(5);
      }])->get();

      //determine if city is followed
      $isFollowed = false;
      $city = CityModel::where(["city_name"=>$city])->first();
      $favorites = Auth::user()->cityFavorites()->pluck("city_id")->toArray();
      if(in_array($city->id, $favorites)){
          $isFollowed=true;
      }

      return view("five-day-forecast", compact("forecast","isFollowed"));
    }

    public function searchAll(Request $request)
    {
        $request->validate([
            "city_name" => "nullable|string",
            "country" => "nullable|string",
            "date" => "nullable|string"
        ]);

        $paramsSent = false;
        foreach ($request->all() as $param) {
            if ($param !== null) {
                $paramsSent = true;
                break;
            }
        }

        if($paramsSent===false){
            return redirect("/weather");
        }

        $forecastQuery = ForecastModel::query();
        // Initialize the variables from the request
        $city_name = $request->city_name;
        $date = $request->date;
        $country = $request->country;
        if($date===null){
            $date=Carbon::now()->format("Y-m-d");
        }
        $forecastQuery->where("date",$date);
        /**********/
        if($city_name!==null){
            $forecastQuery->whereHas("city", function ($query) use ($city_name){
               $query->where("city_name", "LIKE","%$city_name%");
            });
        }
        if($country!==null){
            $forecastQuery->whereHas("city", function ($query) use ($country){
                $query->where("country", "LIKE","%$country%");
            });
        }

        $weathers = $forecastQuery->with("city")->get();

        if(count($weathers)===0){
            return view("/welcome", compact("weathers"))->with("error", "No results matched your criteria");
        }
        //return favorites
        $favoriteCities = Auth::user()->cityFavorites;

        return view("search_results", compact("weathers","favoriteCities"));

    }
    public function test(Request $request)
    {
        Artisan::call("forecast:get", [
            "city" => $request->city_name
        ]);
        //return array
        $weathers = collect([]);
        //raw encoded Json
        $rawForecast = Artisan::output();
        //convert json to associative array
        $forecast = json_decode($rawForecast, true);
        if(array_key_exists("error",$forecast)){
            return view("/welcome", compact("weathers"))->with("error", "No results matched your criteria");
        }
        $cityExists = false;
        //parse location data
        $city_name = $forecast["city_name"];
        $country = $forecast["country"];

        //check if the city is in our records

        //forecast data for next 3 days
        $data = $forecast["forecast"];
        //data = associative array of arrays of data holding each returned date and its properties
        //info =  associative array of all the individual properties
        echo $city_name . ":" . $country . " \n";
        foreach ($data as $index => $info) {
            $forecastExists = false;
            $date = $info["date"];
             $temperature = $info["avg_temp"];
             $description = $info["description"];
             if(str_contains($description, "rain")){
                 $probability = $info["chance_of_rain"] ;
             }else{
                 $probability = $info["chance_of_snow"] ;
             }
            $dbCity = CityModel::where(["city_name"=>$city_name, "country"=>$country])->first();
            if($dbCity!==null){
                $cityExists=true;
            }
             //check for forecast if city exists
            if($cityExists){
                $cityId=$dbCity->id;
                $dbForecast = ForecastModel::where(["date"=>$date, "city_id"=>$cityId])->first();
                echo $dbForecast;
                if($dbForecast!==null){
                    $forecastExists=true;
                    $weathers->push($dbForecast);
                    return view("/welcome", compact("weathers"));
                }
            }
            $newCity = CityModel::class;
            if(!$cityExists){
                //create city and forecast
                $newCity = CityModel::create([
                    "country"=>$country,
                    "city_name"=>$city_name
                ]);
                $newForecast = ForecastModel::create([
                    "city_id"=>$newCity->id,
                    "temperature"=>$temperature,
                    "date"=>$date,
                    "description"=>$description,
                    "probability"=>$probability,
                    "path_to_image"=>"/res/sunny.png"
                ]);
                $weathers->push($newForecast);
            }
            //other option city exists but no forecast
            $newForecast = ForecastModel::create([
                "city_id"=>$dbCity->id,
                "temperature"=>$temperature,
                "date"=>$date,
                "description"=>$description,
                "probability"=>$probability,
                "path_to_image"=>"/res/sunny.png"
            ]);
            $weathers->push($newForecast);

            //reset control booleans for next iteration
            $cityExists=false;
        }
        return view("/welcome", compact("weathers"));
    }




}
