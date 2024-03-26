<?php

namespace App\Http\Controllers;

use App\Models\CityModel;
use App\Models\UserCity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCitiesController extends Controller
{
    public function addToFavorites(CityModel $city){
        //fetch userid (route protected by middelware, so user ID should never be null)
        $userId = Auth::id();
        //JUST IN CASE
        if($userId===null || $city===null){
            return response([
                "success"=>false
            ]);
        }
        //add city
        UserCity::create([
            "user_id"=>$userId,
            "city_id"=>$city->id
        ]);
        //return true
        return response([
            "success"=>Auth::user()->cityFavorites
        ]);
    }
}
