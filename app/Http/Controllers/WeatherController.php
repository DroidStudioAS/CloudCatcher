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


    /*****Admin Functions*****/
    public function getAllWeathersAdmin(){
        $weathers = WeatherModel::paginate(6);

        $cities = CityModel::all();
        foreach ($weathers as $weather){
            $weather->city_id=$weather->city->id;
            $weather->city_name=$weather->city->city_name;
        }


        return view("admin.admin", compact('weathers','cities'));
    }
    public function editWeatherEntry(Request $request, WeatherModel $weather)
    {
        $request->validate([
            "city" => "required|int|gte:1",
            "description" => "required|string",
            "temperature" => 'required|int',
        ]);

        //try and see if the city exists in the db
        $cityEntered = CityModel::where(["id" =>$request->input("city")])->first();
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


    public function postForecastEntry(Request $request){
        //validate request
      $request->validate([
          "city_id"=>"required|int|exists:cities,id",
          "temperature"=>"required|numeric",
          "description"=>"required|string",
          "date"=>"required|date",
          "precipitation"=>"nullable|int"
      ]);
      //get cityId
        $city = CityModel::where(["id"=>$request->input("city_id")])->first();
        $cityId = $city->id;
      //check if forecast for city on date already exists, if it does, change it;
        foreach ($city->forecast as $cast){
            if($cast->date === $request->input("date")){
               //edit current entry
                $cast->city_id=$cityId;
                $cast->temperature=$request->input("temperature");
                $cast->date=$request->input("date");
                $cast->description=$request->input("description");
                $cast->probability=$request->input("precipitation");
                $cast->path_to_image = WeatherHelper::determinePathToImage($request->input("description"));

                $cast->save();

                return redirect()->back();
            }
        }
        //if there are no entries for this city on this date
      //create model
      ForecastModel::create([
          "city_id"=>$cityId,
          "temperature"=>$request->input("temperature"),
          "date"=>$request->input("date") ,
          "description"=>$request->input("description"),
          "probability"=>$request->input("precipitation"),
          "path_to_image"=>WeatherHelper::determinePathToImage($request->input("description"))
      ]);
        //in case of success send user back
      return redirect()->back();
    }
   public function deleteForecastEntry(ForecastModel $forecast)
   {

       $forecast->delete();
       return response([
           "success"=>true
       ]);
   }


    //test function, pay it no mind
    public function test(){
        $i=1;
        foreach (CityModel::all() as $cities){
           foreach ($cities->forecast as $forecast){
               echo $i . " " . $cities->city_name . " " . $forecast->date . " " . $forecast->temperature;
           }
        }
    }


}
