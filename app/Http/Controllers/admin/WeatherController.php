<?php

namespace App\Http\Controllers\admin;

use App\Helpers\WeatherHelper;
use App\Http\Controllers\Controller;
use App\Models\CityModel;
use App\Models\ForecastModel;
use App\Models\WeatherModel;
use Illuminate\Http\Request;

class WeatherController extends Controller
{

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
