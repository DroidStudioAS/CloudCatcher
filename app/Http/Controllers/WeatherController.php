<?php

namespace App\Http\Controllers;

use App\Helpers\WeatherHelper;
use App\Models\CityModel;
use App\Models\ForecastModel;
use App\Models\WeatherModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
      return view("five-day-forecast", compact("forecast"));
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




}
