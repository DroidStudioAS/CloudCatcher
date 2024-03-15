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

}