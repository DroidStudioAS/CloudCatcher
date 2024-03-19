<?php

namespace App\Http\Controllers;

use App\Models\CityModel;
use App\Models\ForecastModel;
use App\Models\WeatherModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\WeatherHelper;

class WeatherController extends Controller
{


    public function loadTodaysWeathers(){
        $date = Carbon::today()->format('Y-m-d');

        $allWeathers = WeatherModel::all();
        $weathers = collect([]);
        foreach ($allWeathers as $weatherEntry){
            $city= CityModel::where(["id"=>$weatherEntry->city_id])->first()->city_name;

            $weatherEntry->city_name=$city;

            $weathers->push($weatherEntry);


        }

        return view("welcome", compact('weathers', 'date'));
    }
    public function getWeathersForDate($date)
    {
        if ($date === null) {
            $date = Carbon::today()->format("Y-m-d");
        }
        $weathers = collect([]);
        $forecast = ForecastModel::all();
        foreach ($forecast as $cast){
            if($cast->date===$date){
                $city= CityModel::where(["id"=>$cast->city_id])->first()->city_name;
                $cast->city_name=$city;
                $weathers->push($cast);
            }

        }
        return view("welcome", compact("weathers","date"));
    }
    public function getWeatherForecastForCity($city){
        $today = Carbon::today()->format("Y-m-d");

        $weathers = collect([]);
        //get id from the city name
        $cityFromDb = CityModel::where(["city_name"=>$city])->first();

        if($cityFromDb===null){
            //return right away (empty array);
            return view("five-day-forecast",compact("weathers", 'city'));
        }
        //city found
        $cityId = $cityFromDb->id;

        $forecast = ForecastModel::where(["city_id"=>$cityId])
            ->orderBy("date", "asc")
            ->latest()
            ->take(5)
            ->get();
        //populate return array
        foreach ($forecast as $cast){
            $cast->city_name=$cityFromDb->city_name;
            $weathers->push($cast);
        }


        //return weathers
        return view("five-day-forecast",compact("weathers",'city'));

    }
    public function getCountryForecast($country){
        dd($country);
    }



    /*****Admin Functions*****/
    public function getAllWeathersAdmin(){
        $weathers = WeatherModel::all();


        return view("admin.admin", compact('weathers'));
    }
    public function postWeatherEntry(Request $request){
        $request->validate([
           'description'=>'required|string',
            'city'=>'required|string',
            'temperature'=>'required|int'
        ]);
        //determine the image path based on desc
        $path_to_image = WeatherHelper::determinePathToImage($request->get("description"));
       //IMAGE PATH DETERMINED
        //build weather model
        WeatherModel::create([
            'city'=>$request->get("city"),
            "description"=>$request->get("description"),
            "temperature"=>$request->get("temperature"),
            "path_to_image"=>$path_to_image
        ]);

        return back();
    }
    function editWeatherEntry(Request $request, WeatherModel $weather){

        $request->validate([
          "city"=>"required|string",
          "description"=>"required|string",
            "temperature"=>'required|int',
        ]);
        //determine image path
        $path_to_image = WeatherHelper::determinePathToImage($request->get("description"));

        $weather->city= $request->input("city");
        $weather->description= $request->input("description");
        $weather->temperature=$request->input("temperature");
        $weather->path_to_image = $path_to_image;


        $weather->save();

        return response([
            'success'=>true
        ]);
    }
    function deleteWeatherEntry(WeatherModel $weather){
        $weather->delete();

        return response([
            'success'=>true
        ]);
    }

}
