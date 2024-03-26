<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserCitiesController extends Controller
{
    public function addToFavorites($city){
        return response([
            "success"=>true
        ]);
    }
}
