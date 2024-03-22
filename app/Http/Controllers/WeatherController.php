<?php

namespace App\Http\Controllers;

use App\Helpers\WeatherHelper;
use App\Models\CityModel;
use App\Models\ForecastModel;
use App\Models\WeatherModel;
use Carbon\Carbon;
use Illuminate\Http\Request;


class WeatherController extends Controller
{
    public function loadTodaysWeathers(){
        $date = Carbon::today()->format('Y-m-d');

        $weathers = WeatherModel::paginate(6);

        foreach ($weathers as $weatherEntry){
            $weatherEntry->city_name=$weatherEntry->city->city_name;
        }
        return view("welcome", compact('weathers', 'date'));
    }
    public function getWeathersForDate($date)
    {
        if ($date === null) {
            $date = Carbon::today()->format("Y-m-d");
        }
        $weathers = ForecastModel::where(['date'=>$date])->paginate(6);
        foreach ($weathers as $cast){
                $cast->city_name=$cast->city->city_name;
        }
        return view("welcome", compact("weathers","date"));
    }
    public function getSearchResults($city_name){
       $weathers = CityModel::where("city_name", "LIKE", "%$city_name%")->get();

       return view("search_results", compact("weathers","city_name"));
    }
    public function getWeatherForecastForCity($city){
        $weathers = collect([]);
        //get id from the city name
        $cityFromDb = CityModel::where(["city_name"=>$city])->first();
        //the reason i did not bind the city, is because we want to return an empty array for the city
        //in case it is not found, and if i were to bind the param, i do not have control of the validation
        if($cityFromDb===null){
            //return right away (empty array);
            return view("five-day-forecast",compact("weathers", 'city'));
        }
        //city found
        $forecast = $cityFromDb->forecast;
        //populate return array
        foreach ($forecast as $cast){
            $cast->city_name=$cast->city->city_name;
            $weathers->push($cast);
        }
        //return weathers
        return view("five-day-forecast",compact("weathers",'city'));

    }
    public function getCountryForecast($country){
        $date = Carbon::today()->format("Y-m-d");

        /*****Need to get the id's of all the countries cities for comparison****/
        $cities = CityModel::where(["country"=>$country])->pluck("id", "city_name");
        $weathers = WeatherModel::whereIn("city_id", $cities->values())->paginate(6);
        //country not found
        if(count($cities)===0){
            return view("welcome", compact("weathers","date", 'country'));
        }


        foreach ($weathers as $weather){
          $weather->city_name=$weather->city->city_name;
        }

        return view("welcome", compact("weathers","date","country"));
    }



}
