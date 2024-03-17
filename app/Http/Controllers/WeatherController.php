<?php

namespace App\Http\Controllers;

use App\Models\WeatherModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WeatherController extends Controller
{

    public function getWeathersForDate($date)
    {
        if ($date === null) {
            $date = Carbon::today()->format("Y-m-d");
        }
        $allWeathers = WeatherModel::all();
        $weathers = collect([]);
        foreach ($allWeathers as $weather){
            $carbonInstance = $weather->created_at;
            $dateString = $carbonInstance->format("Y-m-d");
            if($dateString==$date){
                $weathers->push($weather);
            }
        }
        return view("welcome", compact('weathers', 'date'));
    }
    public function getAllWeathers(){
        $weathers = WeatherModel::all();


        return view("welcome", compact('weathers'));
    }
    public function loadTodaysWeathers(){
        $date = Carbon::today()->format('Y-m-d');

        $allWeathers = WeatherModel::all();
        $weathers = collect([]);
        foreach ($allWeathers as $weather){
            $carbonInstance = $weather->created_at;
            $dateString = $carbonInstance->format("Y-m-d");
            if($dateString==$date){
                $weathers->push($weather);
            }
        }
        return view("welcome", compact('weathers', 'date'));
    }


    /*****Admin Functions*****/
    /****Start of helpers**/
        public function determinePathToImage($description){
            $path_to_image="";
            switch ($description){
                case "sunny":
                    $path_to_image="/res/sunny.png";
                    break;
                case "raining":
                    $path_to_image="/res/rainy.png";
                    break;
                case "cloudy":
                    $path_to_image="/res/cloudy.png";
                    break;
                case "snowing":
                    $path_to_image="/res/snowy.png";
                    break;
            }
            return $path_to_image;
        }
    /****End of helpers****/
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
        $path_to_image = $this->determinePathToImage($request->get("description"));
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
        $path_to_image = $this->determinePathToImage($request->get("description"));

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
