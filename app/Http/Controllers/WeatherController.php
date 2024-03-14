<?php

namespace App\Http\Controllers;

use App\Models\WeatherModel;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function getAllWeathers(){
        $weathers = WeatherModel::all();


        return view("welcome", compact($weathers));
    }
}
