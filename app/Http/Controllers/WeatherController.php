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
        $path_to_image = "";
        //determine the image path based on desc
        switch ($request->get("description")){
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

}
