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
          $cityName = $weather->city->city_name;
          $weather->city_name=$cityName;
        }

        return view("welcome", compact("weathers","date","country"));
    }


    /*****Admin Functions*****/
    public function getAllWeathersAdmin(){
        $weathers = WeatherModel::paginate(6);

        foreach ($weathers as $weather){
            $weather->city_name=$weather->city->city_name;
        }


        return view("admin.admin", compact('weathers'));
    }
    public function postWeatherEntry(Request $request){
       $request->validate([
           'description'=>'required|string',
            'city'=>'required|string|exists:cities,city_name',
            'temperature'=>'required|int'
        ]);

       //validation passed
        //find city in db
        $cityId = CityModel::where(["city_name"=>$request->input("city")])->first()->id;
        $path_to_image = WeatherHelper::determinePathToImage($request->get("description"));
        //build weather model
        WeatherModel::create([
            'city_id'=>$cityId,
            "description"=>$request->get("description"),
            "temperature"=>$request->get("temperature"),
            "path_to_image"=>$path_to_image
        ]);

        return back();

    }
    public function editWeatherEntry(Request $request, WeatherModel $weather)
    {

        //city name of the original entry
        $dbCity = $weather->city->city_name;
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
    public function deleteWeatherEntry(WeatherModel $weather){
        $weather->delete();

        return response([
            'success'=>true
        ]);
    }
    public function test(){
        $i=1;
        foreach (ForecastModel::all() as $weathers){
            echo $i . " " . $weathers->temperature . " " . $weathers->city->country . " " . $weathers->city->city_name ;
            $i++;
        }
    }


}
