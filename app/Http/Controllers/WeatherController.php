<?php

namespace App\Http\Controllers;

use App\Models\WeatherModel;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function getAllWeathers(){
        $weathers = WeatherModel::all();


        return view("welcome", compact('weathers'));
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
    function editWeatherEntry(Request $request, $weatherModel){

        $weatherToEdit = WeatherModel::where(['id'=>$weatherModel])->first();
        if(!$weatherToEdit){
            return  response([
                "success"=>false
                ]);
        }

        $request->validate([
          "city"=>"required|string",
          "description"=>"required|string",
            "temperature"=>'required|int',
        ]);
        //determine image path
        $path_to_image = $this->determinePathToImage($request->get("description"));

        $weatherToEdit->city= $request->input("city");
        $weatherToEdit->description= $request->input("description");
        $weatherToEdit->temperature=$request->input("temperature");
        $weatherToEdit->path_to_image = $path_to_image;


        $weatherToEdit->save();

        return response([
            'success'=>true
        ]);
    }

}
