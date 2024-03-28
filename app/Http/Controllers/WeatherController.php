<?php

namespace App\Http\Controllers;

use App\Helpers\WeatherHelper;
use App\Models\CityModel;
use App\Models\ForecastModel;
use App\Models\UserCity;
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
        //Array to return
        $weathers = collect([]);
        $userFavorites = Auth::user()->cityFavorites->pluck("city_id")->toArray();

        /******If The city exists, see if there is a forecast entry already available
         * if there is, return them without calling the api
         * if not, continue to API call and fetch the forecast for 3 days*******/
        $city = CityModel::where("city_name", "LIKE", "%$request->city_name%")
            ->where("country","LIKE","%$request->country%")
            ->get();
        //city is not empty
        if(!$city->isEmpty()){
            foreach ($city as $name){
                foreach ($name->forecast as $forecast) {
                    //if date is set, add only existing records for that date
                    if($request->date!==null) {
                        if ($request->date === $forecast->date) {
                            $weathers->push($forecast);
                        }
                        //else add all existing records
                    }else{
                        $weathers->push($forecast);
                    }
                }
            }
            if(count($weathers)!==0) {
                return view("/search_results", compact("weathers","userFavorites"));
            }
            $weathers=collect([]);
        }
        /**No forecasts found***/
        $params =[];
        //build request params
        if(!is_null($request->country)){
            $params["country"]=$request->country;
        }
        if(!is_null($request->city_name)){
            $params["city"]= $request->city_name;
        }


        /******Api Call*****/
        Artisan::call("forecast:get", $params);
        /********Start of weather json decoding********/
        //raw encoded Json
        $rawForecast = Artisan::output();
        //convert json to associative array
        $forecast = json_decode($rawForecast, true);
        if(array_key_exists("error",$forecast)){
            return view("/search_results", compact("weathers","userFavorites"))
                ->with("error", "No results matched your criteria");
        }
        /********End of weather json decoding********/

        /********Start of weather json data extraction********/
        //location data
        $city_name = $forecast["city_name"];
        $country = $forecast["country"];

        /********Create the city if it does not exist
         * if it does exist, pull out the first city, because that is the only
         * one we have data for********/
        if($city->isEmpty()){
            $city= CityModel::create([
                "city_name"=>$city_name,
                "country"=>$country
            ]);
        }else{
            $city=$city->first();
        }
        //forecast data for next 3 days
        $data = $forecast["forecast"];
        //data = associative array of arrays of data holding each returned date and its properties
        //info =  associative array of all the individual properties
        foreach ($data as $index => $info) {
            $forecastExists = false; //control boolean
             //forecast data
             $date = $info["date"];
             $temperature = $info["avg_temp"];
             $description = $info["description"];
             if(str_contains($description, "rain")){
                 $probability = $info["chance_of_rain"] ;
             }else{
                 $probability = $info["chance_of_snow"] ;
             }
            /********End of weather json data extraction********/
            //City
            $cityId=$city->id;
            /*****Check If Forecast Exists****/
            $dbForecast = ForecastModel::where(["date"=>$date, "city_id"=>$cityId])->first();
            if($dbForecast!==null){
                //if it exists, return it to user
                $forecastExists=true;
                $weathers->push($dbForecast);
            }
            /*********Forecast Does Not Exist, create it**********/
            if(!$forecastExists){
                $newForecast = ForecastModel::create([
                    "city_id"=>$city->id,
                    "temperature"=>$temperature,
                    "date"=>$date,
                    "description"=>$description,
                    "probability"=>$probability,
                    "path_to_image"=>WeatherHelper::realDataImagePathDeterminer($description)
                ]);
                $weathers->push($newForecast);
            }
            //reset control booleans for next iteration
            $forecastExists=false;
        }
        return view("/search_results", compact("weathers","userFavorites"));
    }
    public function getDailyWeatherForCity(CityModel $city){


        $params =[];
        $params ["city"]=$city->city_name;
        $params["country"]=$city->country;

        $favoriteCities = Auth::user()->cityFavorites;



        Artisan::call("forecast:daily", $params);
        $dailyData = json_decode(Artisan::output(),true);
        if(array_key_exists("error",$dailyData)){
            return view("/welcome", compact("favoriteCities"))->with("error", "No Locations Were Found By $city");
        }

        return view("five-day-forecast", compact("dailyData"));


    }




}
