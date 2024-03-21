<?php

namespace App\Http\Controllers;

use App\Helpers\WeatherHelper;
use App\Models\CityModel;
use App\Models\WeatherModel;
use Illuminate\Http\Request;

class CityController extends Controller
{
    function addCity(Request $request){
        //todo:refactor to make sure that there is not a same city with the same country;
        //validate data
        $request->validate([
            "country"=>"required|string",
            "city_name"=>"required|string|unique:cities,city_name",
            "temperature"=>"required|numeric",
            "description"=>"required|string"
        ]);

        $newCity = CityModel::create([
            "country"=>$request->input("country"),
            "city_name"=>$request->input("city_name")
        ]);

        WeatherModel::create([
            "city_id"=>$newCity->id,
            "temperature"=>$request->input("temperature"),
            "description"=>$request->input("description"),
            "path_to_image"=>WeatherHelper::determinePathToImage($request->input("description"))
        ]);


       return redirect()->back();
    }
}
