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
        $date = Carbon::today()->format("Y-m-d");

        $weathers = collect([]);
        /*****Need to get the id's of all the countries cities for comparison****/
        $cities = CityModel::where(["country"=>$country])->pluck("id", "city_name");

        //country not found
        if(count($cities)===0){
            return view("welcome", compact("weathers","date"));
        }
        //index = city_id $city=city_name
        foreach ($cities as $city=>$index){
           $weatherToAdd = WeatherModel::where(["city_id"=>$index])->first();
           $weatherToAdd->city_name=$city;
           $weathers->push($weatherToAdd);
        }
        return view("welcome", compact("weathers","date","country"));
    }




    /*****Admin Functions*****/
    public function getAllWeathersAdmin(){
        $weathers = WeatherModel::paginate(6);

        foreach ($weathers as $weather){
            $cityId = $weather->city_id;
            $city_name = CityModel::where(['id'=>$cityId])->first()->city_name;
            $weather->city_name=$city_name;
        }


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
    function editWeatherEntry(Request $request, WeatherModel $weather)
    {

        //city name of the original entry
        $dbCity = CityModel::where(["id" => $weather->city_id])->first()->city_name;
        $normalCity = \Normalizer::normalize($dbCity, \Normalizer::FORM_C);
        $uppercaseCity = strtoupper($normalCity);


        $request->validate([
            "city" => "required|string",
            "description" => "required|string",
            "temperature" => 'required|int',
        ]);

        //try and see if the city exists in the db
        $cityEntered = CityModel::where(["city_name" => ucfirst($request->input("city"))])->first();
        //activate this block if not found
        if (!$cityEntered) {
            return response([
                "success" => false
            ]);
        }
        //entered city found, can continue
        $path_to_image = WeatherHelper::determinePathToImage($request->get("description"));

        $weather->city_id = $cityEntered->id;
        $weather->description = $request->input("description");
        $weather->temperature = $request->input("temperature");
        $weather->path_to_image = $path_to_image;

        $weather->save();

        return response([
            'success' => true
        ]);

    }


    function deleteWeatherEntry(WeatherModel $weather){
        $weather->delete();

        return response([
            'success'=>true
        ]);
    }

}
