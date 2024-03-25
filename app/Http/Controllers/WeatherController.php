<?php

namespace App\Http\Controllers;

use App\Helpers\WeatherHelper;
use App\Models\CityModel;
use App\Models\ForecastModel;
use App\Models\WeatherModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;


class WeatherController extends Controller
{
    public function loadTodaysWeathers(){
        $date = Carbon::today()->format('Y-m-d');

        $weathers = ForecastModel::where("date", $date)->paginate(6);

        return view("welcome", compact('weathers', 'date'));
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

        $weathers = $forecastQuery->get();

        if(count($weathers)===0){
            return view("/welcome", compact("weathers"))->with("error", "No results matched your criteria");
        }

        return view("search_results", compact("weathers"));

    }




}
